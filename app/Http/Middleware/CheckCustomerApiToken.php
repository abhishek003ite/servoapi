<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use App\Models\Customer;

use App\Traits\ApiResponses;

class CheckCustomerApiToken
{
    use ApiResponses;
    
    public function handle($request, Closure $next)
    {
        $header = $request->header('Authorization');
        $id = $request->route()->parameters()['id'];

        if (is_null($header)) {
            return $this->errorResponse([
                'message' => 'You need to be logged in first.',
                'login_failed' => true
            ]);
        }

        $parts = explode(' ', $header);

        if($parts[0] != 'Bearer' || count($parts) != 2) {
            return $this->errorResponse([
                'message' => 'Bad Authorization header.',
                'login_failed' => true
            ]);
        }

        $api_token = $parts[1];

        if(is_null($api_token)){
            return $this->errorResponse([
                'message' => 'You need to be logged in first.',
                'login_failed' => true
            ]);
        }

        $user = User::where('api_token', $api_token)->first();
        
        if(is_null($user)){
            return $this->errorResponse([
                'message' => 'You need to be logged in first.',
                'login_failed' => true
            ]);
        }

        $valid_till = $user->api_token_valid_till;

        if(is_null($valid_till)){
            return $this->errorResponse([
                'message' => 'You are logged out. Please log in again.',
                'login_failed' => true
            ]);
        }

        $tokenValidTill = Carbon::createFromFormat('Y-m-d H:i:s', $valid_till);

        if($tokenValidTill->lte(Carbon::now())) {
            return $this->errorResponse([
                'message' => 'You are logged out. Please log in again.',
                'login_failed' => true
            ]);
        }
        
        $customer = Customer::find($id);

        if(is_null($customer)) {
            return $this->errorResponse([
                'message' => 'Your account is invalid or inactive.',
                'login_failed' => true
            ]);
        }

        $user_api_token = User::where('id', $user->id)->first();
        
        if(is_null($user_api_token)){
            return $this->errorResponse([
                'message' => 'Your account is invalid or inactive.',
                'login_failed' => true
            ]);
        }
        
        if($user_api_token->api_token != $api_token) {
            return $this->errorResponse([
                'message' => 'Your account is invalid or inactive.',
                'login_failed' => true
            ]);
        }

        return $next($request);
    }
}
