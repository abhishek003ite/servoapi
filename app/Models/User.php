<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function partner() {
        return $this->hasOne('App\Models\Partner');
    }

    public function customer() {
        return $this->hasOne('App\Models\Customer');
    }

    /**
     * This function is used by the contollers to enforce uniqueness
     * of mobile number. This isn't being done by the database and
     * | unique validations because by the time the client requested
     * this, the DB was already full of NULL and repeated mobile 
     * numbers.
     */    
    public static function isMobileAvailable($number, $user_id) {
        $user = User::find($user_id);
        
        // if the person is trying to update to his same number
        if($user && $user->mobile_number == $number) {
            return true;
        }

        // By now we know that the user doesn't own this mobile number.
        // So, let's check if this mobile already exists.
        $total_found = User::where('mobile_number', $number)->count();
        return !$total_found;
    }

    public function getFullName() {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function renewApiToken() {
        $apiToken = str_random(60);
        $validTill = Carbon::now()->addDays(env('API_TOKEN_VALID_DAYS', 10));

        $this->api_token = $apiToken;
        $this->api_token_valid_till = $validTill;
    }

    public function isApiTokenValid() {
        if($this->api_token && $this->api_token_valid_till) {
            // Check if token is valid
            $tokenValidTill = Carbon::createFromFormat('Y-m-d H:i:s', $this->api_token_valid_till);

            if($tokenValidTill->gt(Carbon::now())) {
                return true;
            }
        }

        return false;
    }

    public function destroyApiToken() {
        $this->api_token = null;
        $this->api_token_valid_till = null;
        $this->save();
    }
}
