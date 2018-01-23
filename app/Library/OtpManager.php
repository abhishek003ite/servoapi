<?php
namespace App\Library;
use App\Models\Otp;
use Carbon\Carbon;
Class OtpManager {
    /**
     * Input: nothing 
     * 
     * Output
     * [
     *      'otp' => '2234',
     *      'txn_id' => '23'
     * ]
     */
    static function generateOtp()
    {
        $code = rand(1001, 9999);
        $otp = new Otp();
        $otp->otp = $code;        
        $otp->valid_till = Carbon::now()
                            ->addMinutes(env('OTP_VALID_MINUTES'))
                            ->toDateTimeString(); 
        $otp->save();
        
        return [
            'otp' => $code,
            'txn_id' => $otp->id
        ];
    }

    /**
     * Input:
     * [
     *      'otp' => '2234',
     *      'txn_id' => '23'
     * ]
     * 
     * output:
     * [
     *      'status' => true and false
     *      'msg'   => 'string'
     * ]
     * 
     */
    static function verifyOtp($data)
    {
        $otp = Otp::find($data['txn_id']);
        
        // Check if transaction exists
        if(!$otp) {
            return [
                'status' => false,
                'msg' => 'Invalid transaction',
            ];
        }

        // Check for time
        $valid_till = $otp->valid_till;
        $valid_till = Carbon::createFromFormat('Y-m-d H:i:s', $valid_till);

        if($valid_till->lt(Carbon::now())) {
            return [
                'status' => false,
                'msg' => 'OTP expired',
            ];
        }

        // Check if OTP matches
        $code = $otp->otp;
        if($code != $data['otp']) {
            return [
                'status' => false,
                'msg' => 'Incorrect OTP',
            ];
        }

        return [
            'status' => true,
            'msg' => 'OTP verified',
        ];
    }

}

