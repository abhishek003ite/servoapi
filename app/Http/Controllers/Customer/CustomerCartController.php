<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\PartnerService;

class CustomerCartController extends Controller
{
    public function getCart($id)
    {
        $customer = Customer::find($id);

        $cart = $customer->cart;
        
        if(!$cart) {
            return $this->successResponse();
        }

        $cart->details = $cart->items;
        return $this->successResponse($cart);
    }

    public function addToCart($id)
    {
        $customer = Customer::find($id);
        $partnerService = PartnerService::find($request->partner_service_id);

        if(!$partnerService) {
            return $this->errorResponse([
                'message' => 'This partner-service combination is invalid.'
            ]);
        }

        $cart = $customer->cart;

        if($cart) {
            $partner = $cart->partner;

            $servicePartner = $partnerService->partner;
            
            // disallow services from other partners
            if($partner->id != $servicePartner->id) {
                return $this->errorResponse([
                    'message' => 'Sorry, you can book only one partner at a time.'
                ]);
            }

            // check if selected service is already there
            if($cart->items) {
                $alreadyChosen = false;

                foreach($cart->items as $item) {
                    if($item->id == $id) {
                        $alreadyChosen = true;
                        break;
                    }
                }

                if($alreadyChosen) {
                    return $this->errorResponse([
                        'message' => 'This partner-service is already in cart.'
                    ]);
                }
            }
        } else {
            $cart = new Cart();
            $cart->customer_id = $customer->id;
            $cart->partner_id = $partner->id;
            $cart->save();
        }

        // All checks cleared
        // Add PartnerService to cart items
        
        $cartItem = new CartItem();
        $cartItem->partner_service_id= $id;
        $cartItem->cart_id = $cart->id;
        $cartItem->save();

        return $this->successResponse();

    }

    public function removeFromCart($id)
    {
        $customer = Customer::find($id);

        $cart = $customer->cart;

        if(!$cart) {
            return $this->errorResponse([
                'message' => 'Nothing in cart to remove.'
            ]);
        }

        // handle case where there is nothing in cart but cart entry exists
        // (shouldn't happen in practice, but necessary to handle)
        if($cart && !$cart->items) {
            return $this->errorResponse([
                'message' => 'Nothing in cart to remove.'
            ]);

            $cart->delete();
        }

        $cartItem = CartItem::find($request->partner_service_id);

        if($cartItem) {
            $cartItem->delete();
        }

        // return success even if item is not found
        return $this->successResponse();
    }

    public function destroyCart($id) {
        $customer = Customer::find($id);
        $customer->cart()->delete();

        return $this->successResponse();
    }
}
