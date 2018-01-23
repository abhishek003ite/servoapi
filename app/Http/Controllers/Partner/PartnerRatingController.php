<?php

namespace App\Http\Controllers\Partner;

use App\Models\Partner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PartnerRatingController extends Controller
{
    public function allRatings($id) {
        $finalArray = [];

        $partner = Partner::find($id);
        $ratings = $partner->ratings;
        
        foreach ($ratings as $rating) {
            $finalArray[] = [
                'customer_id' => $rating->customer_id,
                'customer_name' => $rating->customer->user->first_name . ' ' . $rating->customer->user->last_name,
                'rating' => $rating->rating,
                'comments' => $rating->comments
            ];
        }

        return $this->successResponse(['ratings' => $finalArray]);
    }
}
