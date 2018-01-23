<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Library\Common;
use Validator;

use App\Models\Customer;
use App\Models\Partner;
use App\Models\PartnerRating;

class CustomerRatingController extends Controller
{
    // all the ratings given by this customer
    public function getAllPartnerRatings(Request $request, $id) {
        $customer = Customer::find($id);

        $finalArray = [];

        $ratings = PartnerRating::where('customer_id', $id)->get();

        foreach ($ratings as $rating) {
            $partner = Partner::find($rating->partner_id);

            $finalArray[] = [
                'partner_id'=> $partner->id,
                'partner_name' => $partner->user->first_name . 
                ' ' . $partner->user->last_name,
                'rating' => $rating->rating,
                'comments' => $rating->comments
            ];
        }

        return $this->successResponse(['ratings' => $finalArray]);
    }

    public function ratePartner(Request $request, $id, $partner_id) {
        $rating = PartnerRating::where('customer_id', $id)->where('partner_id', $partner_id)->get()->first();

        if(!$rating) {
            $rating = new PartnerRating();
            $rating->partner_id = $partner_id;
            $rating->customer_id = $id;
            $rating->save();
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|numeric|max:5|min:0.5',
        ], Common::getValidationMessageFormat());

        if($validator->fails()) {
            $errors = $validator->errors();
            $first_error = $errors->all()[0];

            return $this->errorResponse(['message'=> $first_error]);
        }

        $rating->rating = $request->rating;
        $rating->comments = $request->comments;

        $rating->save();
        
        return $this->successResponse();
    }
}
