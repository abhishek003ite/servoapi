<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponses;
use App\Models\Partner;
use Closure;

class CheckPartnerActive
{
    use ApiResponses;
    
    public function handle($request, Closure $next)
    {
        $id = $request->route()->parameters()['id'];
        
        if(!$id) {
            return $this->errorResponse([
                'message' => 'Invalid account.',
            ]);
        }

        $partner = Partner::find($id);

        if(!$partner) {
            return $this->errorResponse([
                'message' => 'Your account does not exist or is inactive.',
            ]);
        }

        $active = $partner->is_active;
        if($active == 'no')
        {
            return $this->errorResponse([
                'message' => 'Your account is inactive.',
            ]);
        }

        return $next($request);
    }
}
