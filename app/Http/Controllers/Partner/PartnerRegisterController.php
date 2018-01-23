<?php

namespace App\Http\Controllers\Partner;

use App\Library\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Validator;

use App\Models\Partner;
use App\Models\User;
use App\Models\Address;
use App\Library\OtpManager;
use App\Http\Controllers\Controller;
use App\Library\FileManager;

class PartnerRegisterController extends Controller
{
    public function registerEmail(Request $request) {

        $validator = Validator::make($request->all(), [
            'email'         => 'required|email|unique:users',
            'password'      => 'required|min:5',
            're_password'   => 'required|same:password' 
        ]);

        if($validator->fails()){
            $errors = $validator->errors();
                
            $first_error = $errors->all()[0];

            return $this->errorResponse(['message' => $first_error]);
        }

            $confirmation_code = str_random(30);
            $data = array('email'   =>  $request->email, 'confirmation_code' =>  $confirmation_code);
            $user = new User;
            $user->email = $request->email;
            $user->is_partner = 'yes';
            $user->email_confirmation_code = $confirmation_code;
            $user->password = Hash::make($request->password);
            $user->save();

            $partner = new Partner;
            $partner->user_id = $user->id;
            $partner->save();

            Mail::send('email.verify_partner', $data,  function($message) use ($data){
                $message->subject('Thanks for registering as a Partner with ServoQuick.');
                $message->to($data['email']);

            });

            return $this->successResponse([]);
    }

    public function getPartnerDetails(Request $request, $id) {
        $partner = Partner::find($id);
        $user = $partner->user;

        $responseData = [];

        if($partner->profile_photo_file) {
            $responseData['profile_photo'] = env('FILE_STORAGE_URL') . $partner->profile_photo_file;
        }

        $responseData['first_name'] = $user->first_name;
        $responseData['last_name'] = $user->last_name;
        $responseData['country_code'] = $user->mobile_country_code;
        $responseData['mobile_number'] = $user->mobile_number;
        $responseData['email'] = $user->email;
        $responseData['time_zone_id'] = $user->time_zone_id;
        
        $responseData['address'] = [];

        $address = $user->partner->address;

        if($address != null) {
            $responseData['address']['building_num'] = $address->building_num;
            $responseData['address']['street_address'] = $address->street_address;
            $responseData['address']['landmark'] = $address->landmark;
            $responseData['address']['region'] = $address->city;
            $responseData['address']['city'] = $address->region;
            $responseData['address']['state'] = $address->state;
            $responseData['address']['pincode'] = $address->pincode;
            $responseData['address']['country_id'] = $address->country_id;
        }

        return $this->successResponse($responseData);
    }

    /**
     * Fill Partner Details using id
     */
    public function updatePartnerDetails(Request $request, $id)
    {
        if (!$id) {
            return $this->errorResponse(['message' => "Invalid partner id. Please retry."]);
        }

        $partner = Partner::find($id);
        $user = $partner->user;

        if (!$partner || $partner->count() == 0) {
            return $this->errorResponse(['message' => 'Invalid partner account. Please try again.']);
        } 
        
        if ($user->is_email_confirmed == 'yes') {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'mobile_country_code' => 'required|digits_between:1,3|numeric',
                'mobile_number' => 'required|digits_between:4,20|numeric',
                'building_num'  => 'required',
                'street_address' => 'required',
                'city'  => 'required',
                'pincode' => 'required',
                'state' => 'required',
                'country_id' => 'required|numeric',
                'time_zone_id' => 'required|numeric'

            ], Common::getValidationMessageFormat());

            if ($validator->fails()) {
                $errors = $validator->errors();

                $first_error = $errors->all()[0];
                return $this->errorResponse(['message' => $first_error]);
            }

            // check uniqueness of mobile number (only for new signups)
            if(!User::isMobileAvailable($request->mobile_number, $user->id)) {
                return $this->errorResponse(['message' => 'This mobile number is already in use.']);
            }

            $address = $partner->address ? $partner->address : new Address();

            $address->building_num = $request->building_num;
            $address->street_address = $request->street_address;
            $address->landmark = $request->landmark;
            $address->region = $request->region;
            $address->city = $request->city;
            $address->pincode = $request->pincode;
            $address->state = $request->state;
            $address->country_id = $request->country_id;
            $address->save();


            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->mobile_country_code = $request->mobile_country_code;
            $user->mobile_number = $request->mobile_number;
            $user->time_zone_id = $request->time_zone_id;
            $user->save();
            
            if($request->hasFile('profile_photo')) {
                $partner->profile_photo_file = FileManager::store($request->profile_photo);
            }

            $partner->address_id = $address->id;
            $partner->save();
                      
            return $this->successResponse();               
        }
        
        return $this->errorResponse(['message' => 'Please verify before moving further.']);
    }

    /**
     * Email Confirmation function
     */

    public function verifyEmail(Request $request){
        $failure_message = 'Verification failed. Wrong email or verification code.';

        if(!$request->email){
            return $this->errorResponse(['message' => $failure_message]);
        }

        $user = User::where('email', $request->email)->first();

        if($user->is_email_confirmed == 'yes') {
            return $this->successResponse(['message' => 'Email already verified. Please login to your account.']);
        }
        
        if(!$request->code){
            return $this->errorResponse(['message' => $failure_message]);
        }      

        $user = User::where('email', $request->email)->where('email_confirmation_code', $request->code)->first();

        if (!$user) {
            return $this->errorResponse(['message' => $failure_message]);
        }

        $user->is_email_confirmed = 'yes';
        $user->email_confirmation_code = null;
        $user->save();

        $partner = $user->partner;
        $partner->is_active = 'yes';
        $partner->save();

        return $this->successResponse(['partner_id' => $partner->id]);
    }

    public function getMobileVerifiedStatus($id) {
        $partner = Partner::find($id);

        return $this->successResponse([
            'mobile_verified' => $partner->user->is_mobile_verified == 'yes' ? true : false
        ]);
    }
}