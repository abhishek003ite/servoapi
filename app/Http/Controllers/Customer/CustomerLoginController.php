<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Mail;
use Hash;
use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Customer;


class CustomerLoginController extends Controller
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

        $customer = $user->customer;

        if(!$customer){
            return $this->errorResponse([
                'message' => 'No corresponding customer account found.'
            ]);
        }

        if($customer->is_active == "no"){
            return $this->errorResponse([
                'message' => 'Your account is inactive.'
            ]);
        }

        if(!$user->isApiTokenValid()) {
            $user->renewApiToken();
            $user->save();
        }

        return $this->successResponse([
            'api_token' =>  $user->api_token,
            'customer_id' => $customer->id
        ]);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'email'         => 'required|email|unique:users',
            'first_name'    => 'required',
            'last_name'     => 'required',
            'password'      => 'required'
        ]);

        if($validator->fails()){
            $errors = $validator->errors();                
            $first_error = $errors->all()[0];
            return $this->errorResponse(['message' => $first_error]);
        }

        $confirmation_code = str_random(30);
        $data = array('email'   =>  $request->email, 'confirmation_code' =>  $confirmation_code);
        
        $user = new User;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->password = Hash::make($request->password);
        $user->email = $request->email;
        $user->is_customer = 'yes';
        $user->email_confirmation_code = $confirmation_code;
        $user->save();

        $customer = new Customer();
        $customer->user_id = $user->id;
        $customer->save();

        Mail::send('email.verify_customer', $data,  function($message) use ($data){
            $message->subject('Thanks for registering with ServoQuick!');
            $message->to($data['email']);
        });

        return $this->successResponse([
            'customer_id' => $customer->id
        ]);
    }

    public function logout($id) {
        $user = Customer::find($id)->user;

        $user->api_token = NULL;
        $user->api_token_valid_till = NULL;
        
        $user->save();
        return $this->successResponse([]);
    }
}
