<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Middleware to add version string to the HTTP response.
 * This is used by mobile app developers to validate their
 * app versions, and whether they need to update.
 */
class MobileVersionString
{
    public function handle($request, Closure $next)
    {
        $response =  $next($request);

        // Headers for partner app
        $response->header('X-iOS-Partner-Version', env('IOS_PARTNER_MOBILE_VERSION_STRING','1.0'));
        $response->header('X-Android-Partner-Version', env('ANDROID_PARTNER_MOBILE_VERSION_STRING','1.0'));

        // Headers for customer app
        $response->header('X-iOS-Customer-Version', env('IOS_CUSTOMER_MOBILE_VERSION_STRING','1.0'));
        $response->header('X-Android-Customer-Version', env('ANDROID_CUSTOMER_MOBILE_VERSION_STRING','1.0'));
        
        return $response;
    }
}
