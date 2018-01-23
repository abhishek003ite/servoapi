<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;

use App\Models\User;
use App\Models\Customer;
use App\Library\Common;
use App\Library\SmsSender;

class CustomerOtpController extends Controller
{
    public function checkMobileAvailability(Request $request) {
        $result = $this->validateOrError($request, [
            'mobile' => 'required|numeric|digits:10'
        ]);

        if(!$result['passed']) {
            return $this->errorResponse([
                'message' => $result['message']
            ]);
        }

        if(User::isMobileAvailable($request->mobile, 0)) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }

    public function sendOtp(Request $request) {  
        $result = $this->validateOrError($request, [
            'country_code'   => 'required|digits_between:1,3|numeric',
            'mobile_number'  => 'required|digits_between:4,10|numeric'
        ]);

        if(!$result['passed']) {
            return $this->errorResponse([
                'message' => $result['message']
            ]);
        }

        // Pass through for local environments when no mobile is mentioned
        if(env('APP_ENV') == 'local') {
            return $this->successResponse([                    
                'txn_id' => 'zzz'
            ]);
        }        

        // Use SMS Manager to send SMS
        $txn_id = SmsSender::send([
            "mobile" => $request->mobile_number,
            "country_code" => $request->country_code,
        ]);

        return $this->successResponse([                    
            'txn_id' => $txn_id
        ]);                   

    }

    public function verifyOtp(Request $request, $id) {
        $result = $this->validateOrError($request, [
            'txn_id'        => 'required',
            'otp'           => 'required'
        ]);

        if(!$result['passed']) {
            return $this->errorResponse([
                'message' => $result['message']
            ]);
        }

        $customer = Customer::find($id);

        if(!$customer) {
            return $this->errorResponse([
                'message' => 'Invalid customer id'
            ]);
        }

        $user = $customer->user;
        $user->mobile_number = $request->mobile;
        $user->save();

        // Pass through for local environment
        if(env('APP_ENV') == 'local') {
            $user->renewApiToken();
            $user->is_mobile_verified = "yes";
            $user->save();

            return $this->successResponse([
                'id' => $user->customer->id,
                'api_token' =>  $user->api_token
            ]);
        }

        $status = SmsSender::verify([
            "txt_id" => $request->txn_id,
            "token" => $request->otp,
        ]);

        if($status == 'FAILED') {
            return $this->errorResponse(['message' => 'Incorrect OTP']);
        }

        $user->renewApiToken();
        $user->is_mobile_verified = 'yes';
        $user->save();

        return $this->successResponse([
            'id' => $user->customer->id,
            'api_token' =>  $user->api_token
        ]);
    }
}
