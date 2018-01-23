<?php

namespace App\Http\Controllers\Partner;

use App\Models\Partner;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use Validator;
use App\Http\Controllers\Controller;

class PartnerLoginController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email'         => 'required|email',
            'password'      => 'required',
        ]);

        if($validator->fails()){
            $errors = $validator->errors();
            $first_error = $errors->all()[0];

            return $this->errorResponse([
                'message' => $first_error
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if(is_null($user)) {
            return $this->errorResponse([
                'message' => 'No such account found. Please check your email and password.'
            ]);
        }

        if(!Hash::check($request->password, $user->password)) {
            return $this->errorResponse([
                'message' => 'Incorrect password.'
            ]);
        }

        $partner = $user->partner;

        if(!$partner){
            return $this->errorResponse([
                'message' => 'No corresponding partner account found.'
            ]);
        }

        if($partner->is_active == "no"){
            return $this->errorResponse([
                'message' => 'Partner account inactive. Please verify email first.'
            ]);
        }

        if(!$user->isApiTokenValid()) {
            $user->renewApiToken();
            $user->save();
        }

        return $this->successResponse([
            'api_token' =>  $user->api_token,
            'partner_id' => $partner->id,
            'profile_completed' => $partner->profile_completed,
            'mobile_verified' => $user->is_mobile_verified,
        ]);
    }
    
    public function forgotPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' =>  'required|email'
        ]);

        if($validator->fails()){
            $errors = $validator->errors();
            $first_error = $errors->all()[0];

            return $this->errorResponse([
                'message' => $first_error
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if( !$user ){
            return $this->errorResponse([
                'message' => 'No such account found. Please check your email.'
            ]);
        }

        $confirmation_code = str_random(30);

        $data = array('email'   =>  $request->email, 'confirmation_code'    =>  $confirmation_code);

        Mail::send('email.forgetPassword', $data,  function($message) use ($data){
            $message->subject('Request For Reset Password.');
            $message->to($data['email']);

        });
        return $this->successResponse([

        ]);
    }

    public function resetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'email'             =>  'required|email',
            'password'          =>  'required|min:5',
            're_password'       =>  'required|same:password',
            'code'              =>  'required'
        ]);

        if($validator->fails()){
            $errors = $validator->errors();
            $first_error = $errors->all()[0];

            return $this->errorResponse([
                'message' => $first_error
            ]);
        }

        $user = User::where('email', $request->email)->where('email_confirmation_code', $request->confirmation_code)->first();

        if( !$user ){
            return $this->errorResponse([
                'message' => 'No such account found. Please check your email.'
            ]);
        }

        $user->password = Hash::make($request->password);
        $forgetApiToken = str_random(60);
        $validTill = Carbon::now()->addDays(env('API_TOKEN_VALID_DAYS', 10));

        $user->forgot_password_token = $forgetApiToken;
        $user->save();
        $partner = $user->partner;
        return $this->successResponse([
            'partner_id' => $partner->id,
            'profile_completed' => $partner->profile_completed
        ]);
    }
    
    public function logout($id) {
        $user = Partner::find($id)->user;

        $user->api_token = NULL;
        $user->api_token_valid_till = NULL;
        
        $user->save();
        return $this->successResponse([]);
    }
}