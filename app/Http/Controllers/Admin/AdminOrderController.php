<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\Partner;
use App\Models\Admin;
use App\Models\OrderItem;

class AdminOrderController extends Controller
{
    public function getOrders(Request $request, Admin $admin) {
        $orders = Order::all();
        $ordersArray = [];
        $services = '';

        foreach ($orders as $order) {
            $items = $order->items;

            foreach($items as $item) {
                $services .= $item->service_name;
            }

            $ordersArray[] = [
                'id' => $order->id,
                'created' => $order->getCreatedDate(),
                'customer_name' => $order->customer->user->getFullName(),
                'customer_mobile' => $order->customer->user->mobile,
                'customer_email' => $order->customer->user->email,
                'partner_name' => $order->partner->user->getFullName(),
                'partner_mobiorderle' => $order->partner->user->mobile,
                'services' => $services,
                'visitation_charges' => $order->visitation_charges,
                'total' => $order->total,
                'status' => $order->status
            ];
        }

        return $this->successResponse([
            'orders' => $ordersArray
        ]);
    }

    public function changeStatus(Request $request, $admin_id, $order_id) {
        $order = Order::find($order_id);

        if(!$order) {
            return $this->errorResponse([
                'message' => 'No such order found'
            ]);
        }

        $valid = $this->validateOrError($request, [
            'status' => 'required',
        ]);

        if(!$valid['passed']) {
            return $this->errorResponse([
                'message' => $valid['message']
            ]);
        }

        $status = $request->status;

        if(!Order::isStatusValid($status)) {
            return $this->errorResponse([
                'message' => 'Invalid order status'
            ]);
        }

        $order->status = $status;
        $order->save();

        return $this->successResponse();
    }

    // Assign the order to someone else
    public function reassign(Request $request, $admin_id, $order_id) {
        $order = Order::find($order_id);

        if(!$order) {
            return $this->errorResponse([
                'message' => 'No such order found'
            ]);
        }

        $valid = $this->validateOrError($request, [
            'partner_id' => 'required',
        ]);

        if(!$valid['passed']) {
            return $this->errorResponse([
                'message' => $valid['message']
            ]);
        }

        $partner = Partner::find($request->partner_id);

        if(!$partner) {
            return $this->errorResponse([
                'message' => 'Invalid partner id'
            ]);
        }

        $order->partner_id = $partner->id;
        $order->save();

        return $this->successResponse();
    }
}
