<?php

namespace App\Http\Controllers\Partner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Partner;

class PartnerOrderController extends Controller
{
    public function getOrders($id) {
        // filter ignored for now
        $partner = Partner::find($id);
        $orders = $partner->orders()->where('status', 'completed')->get();
        
        $finalArray = [];

        foreach($orders as $order) {
            $items = $order->items;
            
            $arr1 = [];
            $arr1['id'] = $order->id;
            $arr1['customer_name'] = $order->customer->user->first_name . ' ' . $order->customer->last_name;
            $arr1['date'] = $order->updated_at;
            $arr1['price'] = $order->total;

            $arr2 = [];

            foreach($items as $item) {
                $arr2['service_name'] = $item->service_name;
            }

            $arr1['details'] = $arr2;
            $finalArray[] = $arr1;
        }

        return $this->successResponse(['orders' => $finalArray]);
    }
}
