<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Customer;

class AdminCustomerController extends Controller
{
    public function getAllCustomers() {
        $customers = Customer::all();
        $customerArray = [];

        foreach($customers as $customer) {
            $customerArray[] = [
                'created' => $customer->created_at->toDateTimeString(),
                'name' => $customer->user->getFullName(),
                'mobile' => $customer->user->mobile_number,
                'email' => $customer->user->email,
                'current_location' => '',
                'service_location' => ''
            ];
        }

        return $this->successResponse([
            'customers' => $customerArray
        ]);

    }
}
