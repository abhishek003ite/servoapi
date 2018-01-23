<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Partner;
use App\Models\PartnerService;
use App\Models\Service;

class AdminLoginController extends Controller
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
        if(!$user) {
            return $this->errorResponse([
                'message' => 'No such account found. Please check your email and password.'
            ]);
        }
        if(!Hash::check($request->password, $user->password)) {
            return $this->errorResponse([
                'message' => 'Incorrect password.'
            ]);
        } 
        
        $apiToken = str_random(60);
        $validTill = Carbon::now()->addDays(env('API_TOKEN_VALID_DAYS', 10));

        $user->api_token = $apiToken;
        $user->api_token_valid_till = $validTill;
        $user->save();

        if($user->is_admin == "no"){
            return $this->errorResponse([
                'message' => 'Admin account inactive.'
            ]);
        }

        return $this->successResponse([
            'id' => $user->id,
            'api_token' =>  $apiToken,
        ]);

    }

    public function logout(Admin $admin) {
        $admin->user->destroyApiToken();
        return $this->successResponse();
    }   
}
