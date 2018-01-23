<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Partner;
use App\Models\PartnerService;
use App\Models\Payment;

use App;

class CustomerPaymentController extends Controller
{
    public function initiatePayment(Request $request) {
        $error = '';
        $params = $request->params;
        
        if(!$params) {
            $error = 'URL parameters missing.';
        }

        $params = json_decode(base64_decode($params), true);

        $customer = Customer::find($params['customer_id']);
        $partner = Partner::find($params['partner_id']);

        if(!$customer) {
            $error = 'Invalid customer.';
        }

        if(!$partner) {
            $error = 'Invalid partner.';
        }

        // create new order
        $order = new Order();
        $order->customer_id = $customer->id;
        $order->partner_id = $partner->id;
        $order->visitation_charges = $partner->visitation_charges;
        
        // Check whether we need to add or find percentage
        $charge_type = env('SERVO_SHARE_VISITATION_CHARGES_TYPE');

        $order->servo_visitation_charge_share = 
            env('SERVO_SHARE_VISITATION_CHARGES');

        $order->save();

        // total price of all services in this order
        $servicesTotal = 0;

        $service_ids = $params['services'];

        foreach ($service_ids as $id) {
            $service = PartnerService::find($id);
            if(!$service) {
                $error = "Partner service id $id not found.";
                break;
            }

            // total collected here
            $servicesTotal += $service->service_price;

            // make entry in order_items
            $item = new OrderItem();
            $item->order_id = $order->id;
            $item->partner_id = $partner->id;
            $item->partner_service_id = $service->id;
            $item->service_name = $service->service->name;
            $item->service_charges = $service->service_price;
            $item->save();
        }

        $order->servo_service_charge_share = $servicesTotal * (env('SERVO_SHARE_SERVICE_CHARGES') / 100);

        // Set final price of the order
        $order->total = $servicesTotal + 
                        $order->visitation_charges +
                        $order->servo_visitation_charge_share +
                        $order->servo_service_charge_share;        

        // add GST
        $order->taxes = $order->total * env('GST') / 100;
        $order->total = $order->total + $order->taxes;

        // add convenience charge
        $order->charges = $order->total * env('CONV_CHARGE') / 100;
        $order->total = $order->total + $order->charges;

        $order->save();

        $payment_url = App::environment('production') 
                ? env('PAYU_LIVE_URL') 
                : env('PAYU_TEST_URL');
              
        $amount = $order->total;
        $first_name = $customer->user->first_name;
        $email = $customer->user->email;

        // Create payment related to this order
        $txn_id = str_random(20);
        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->gateway = env('PAYMENT_GATEWAY_NAME');
        $payment->transaction_id = $txn_id;
        $payment->amount = $amount;
        $payment->save();

        // generate order data for view
        $hash_seq = 
              env('PAYU_KEY') . '|' 
            . $txn_id . '|'
            . $amount . '|'
            . env('PAYU_PRODUCT_INFO') . '|'
            . $first_name . '|'
            . $email . '|'
            . 'nothing' . '|' // udf1
            . 'nothing' . '|' // udf2
            . 'nothing' . '|' // udf3
            . 'nothing' . '|' // udf4
            . 'nothing' . '|' // udf5
            . '|' // udf6
            . '|' // udf7
            . '|' // udf8
            . '|' // udf9
            . '|' // udf10
            . env('PAYU_SALT')
        ;

        $hash = strtolower(hash("sha512", $hash_seq));
        
        return view('customer/initiate_payment',
            [
                'error' => $error,
                'payment_url' => $payment_url,
                'amount' => $amount,
                'hash' => $hash,
                'first_name' => $customer->user->first_name,
                'email' => $email,
                'product_info' => env('PAYU_PRODUCT_INFO'),
                'txn_id' => $txn_id,
                'key' => env('PAYU_KEY'),
                'mobile' => $customer->user->mobile_number,
                'success_url' => route('customer-payment-success'),
                'failure_url' => route('customer-payment-failure'),
            ]
        );
    }

    public function successfulPayment(Request $request) {
        $data = $request->all();

        $error = '';

        if(!$request->has('mihpayid')) {
            $error = 'Error: No gateway reference id found.';
        }

        if(!$request->has('hash')) {
            $error = 'Error: No hash found.';
        }

        if(!$request->has('status') || $request->status != 'success') {
            $error = 'Error: Unexpected or missing status.';
        }

        if(!$request->has('txnid')) {
            $error = 'Error: No transaction id found.';
        }

        // if error, show it and die
        if($error) {
            echo '<h2>' . $error . '</h2>';
            die();
        }

        // collect parameters
        $txn_id = $request->txnid;
        $gateway_ref = $request->mihpayid;
        $hash = $request->hash;

        // check for non-existant transaction id
        $payment = Payment::where('transaction_id', $txn_id)->first();

        if(!$payment) {
            echo '<h2>Error: Invalid transaction id.</h2>';
            die();
        }

        // compute and compare hash
        $customer = $payment->order->customer;
        $amount = $payment->amount;
        $first_name = $customer->user->first_name;
        $email = $customer->user->email;

        $status = $request->status;

        $hash_seq = 
            env('PAYU_SALT') . '|' .
            $status . '|' .
            '|' .
            '|' .
            '|' .
            '|' .
            '|' .
            'nothing|' .
            'nothing|' .
            'nothing|' .
            'nothing|' .
            'nothing|' .
            $email . '|' .
            $first_name . '|' .
            env('PAYU_PRODUCT_INFO') . '|' .
            $amount . '|' .
            $txn_id . '|' .
            env('PAYU_KEY');

        $computed_hash = strtolower(hash("sha512", $hash_seq));

        if($hash != $computed_hash) {
            echo '<h2>Error: Checksum mismatch.</h2>';
            die();
        }

        // reaching here means everything happened according to plan!
        $payment->gateway_ref_id = $gateway_ref;
        $payment->status = 'completed';
        $payment->save();
        
        // also mark the order as completed
        $order = $payment->order;
        $order->status = 'paid';

        $order->save();
        
        return view('customer/payment_success');
    }

    public function failedPayment(Request $request) {
        return view('customer/payment_failed');       
    }    
}
