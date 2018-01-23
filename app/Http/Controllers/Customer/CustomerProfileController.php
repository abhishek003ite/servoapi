<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Customer;

class CustomerProfileController extends Controller
{
    public function updateLocation(Request $request, Customer $customer) {
        if(!$request->lat && !$request->lng) {
            return $this->errorResponse([
                'message' => 'Incomplete location data'
            ]);
        }

        $customer->lat = $request->lat;
        $customer->lng = $request->lng;
        $customer->save();

        return $this->successResponse();
    }
}
