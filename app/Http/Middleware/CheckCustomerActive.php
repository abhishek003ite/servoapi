<?php

namespace App\Http\Middleware;

use App\Models\Customer;
use App\Traits\ApiResponses;
use Closure;

class CheckCustomerActive
{
    use ApiResponses;

    public function handle($request, Closure $next)
    {
        $id = $request->route()->parameters()['id'];
        
        if(!$id) {
            return $this->errorResponse([
                'message' => 'Your account does not exist or is inactive.',
            ]);
        }

        $customer = Customer::find($id);

        if(!$customer) {
            return $this->errorResponse([
                'message' => 'Your account does not exist or is inactive.',
            ]);
        }

        $active = $customer->is_active;
        
        if($active == 'no')
        {
            return $this->errorResponse([
                'message' => 'Your account does not exist or is inactive.',
            ]);
        }

        return $next($request);
    }
}
