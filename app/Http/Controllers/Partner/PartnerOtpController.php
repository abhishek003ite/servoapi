<?php

namespace App\Http\Controllers\Partner;

use Illuminate\Http\Request;
use App\Models\Otp;
use Validator;

use App\Models\Partner;
use App\Library\OtpManager;
use App\Http\Controllers\Controller;
use App\Library\Common;
use App\Library\SmsSender;


class PartnerOtpController extends Controller
{
    public function sendOtp(Request $request) {  
        $validator = Validator::make($request->all(), [
            'country_code'   => 'required|digits_between:1,3|numeric',
            'mobile_number'  => 'required|digits_between:4,10|numeric'
        ], Common::getValidationMessageFormat());

        if($validator->fails()) {
            $errors = $validator->errors();
            $first_error = $errors->all()[0];

            return [
                'status'    =>  'error',
                'info'      =>  [
                    'message'    => $first_error
                ]
            ];
        }

        // Pass through for local environments when no mobile is mentioned
        if(env('APP_ENV') == 'local') {
            return [
                'status' => 'ok',
                'info' => [
                    'message' => 'OTP sent',
                    'txn_id' => 'zzz'
                ],
            ];
        }
        

        // Use SMS Manager to send SMS
        $txn_id = SmsSender::send([
            "mobile" => $request->mobile_number,
            "country_code" => $request->country_code,
        ]);

        return [
            'status' => 'ok',
            'info' => [
                'txn_id' => $txn_id,
            ],
        ];
    }

    public function verifyOtp(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'txn_id'        => 'required',
            'otp'           => 'required',
        ]);

        if($validator->fails()) {
            $errors = $validator->errors();
            $first_error = $errors->all()[0];

            return [
                'status'    =>  'error',
                'info'      =>  [
                    'message'    => $first_error
                ]
            ];
        }

        // Pass through for local environment
        if(env('APP_ENV') == 'local') {
            $user = Partner::find($id)->user;
            $user->is_mobile_verified = "yes";
            $user->save();

            return [
                'status'    =>  'ok',
                'info'      =>  [
                ],
            ];
        }

        $status = SmsSender::verify([
            "txt_id" => $request->txn_id,
            "token" => $request->otp,
        ]);

        if($status == 'FAILED') {
            return $this->errorResponse(['message' => 'Incorrect OTP']);
        }

        $user = Partner::find($id)->user;
        $user->is_mobile_verified = "yes";
        $user->save();

        return $this->successResponse([]);
    }
}