<?php

namespace App\Http\Controllers\Partner;

use App\Library\Common;
use App\Library\FileManager;
use App\Models\Partner;
use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\PortfolioPhoto;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Collection;

class PartnerProfileController extends Controller
{
    /**
     * @param Request $request
     * @param $id
     * @return response
     */
    //Saving Profile data to partners table
    public function store(Request $request, $id) {
        $partner = Partner::find($id);

        if($partner->is_active == 'no') {
            return $this->errorResponse([
                'message' => 'Your account is not active.'
            ]);
        }

        
        $validator = Validator::make($request->all(), [
            'about' => 'max:350'
        ], Common::getValidationMessageFormat());

        if($validator->fails()) {
            $errors = $validator->errors();
            $first_error = $errors->all()[0];

            return $this->errorResponse(['message'=> $first_error]);
        }

        if(!is_null($request->profile_photo_file)) {
            $partner->profile_photo_file = FileManager::store($request->profile_photo_file);
        }
        
        $partner->about = $request->about;

        // Comment out initial features not being used now

        // $portfolio = new Portfolio;
        // $portfolio_photo = new PortfolioPhoto;
        // for ($i = 0; $i < count($request->previous_work); $i++) {
        //     $portfolio->name = $request->previous_work[$i]->getClientOriginalName();
        //     $portfolio->partner_id = $id;
        //     $portfolio->save();

        //     $portfolio_photo->file = FileManager::store($request->previous_work[$i]);
        //     $portfolio_photo->portfolio_id = $portfolio->id;
        //     $portfolio_photo->save();
        // }

        // $time_slots = new TimeSlot;

        // for($j = 0; $j < count($request->time_slots); $j++){
        //     $time_slots->start_time = $request->time_slots[$j]['start_time'];
        //     $time_slots->end_time = $request->time_slots[$j]['end_time'];
        //     $time_slots->visitation_price = $request->time_slots[$j]['visitation_price'];
        //     $time_slots->partner_id = $id;

        //     $time_slots->save();
        // }

        $partner->works_mondays = $request->works_mondays;
        $partner->works_tuesdays = $request->works_tuesdays;
        $partner->works_wednesdays = $request->works_wednesdays;
        $partner->works_thursdays = $request->works_thursdays;
        $partner->works_fridays = $request->works_fridays;
        $partner->works_saturdays = $request->works_saturdays;
        $partner->works_sundays = $request->works_sundays;

        $partner->work_timing_start = $request->work_timing_start;
        $partner->work_timing_end = $request->work_timing_end;

        $partner->service_location_lat = $request->service_location_lat;
        $partner->service_location_long = $request->service_location_long;
        $partner->service_radius_km = $request->service_radius_km;

        $partner->save();

        return $this->successResponse();
    }

    /**
     * Get Profile
     */
    public function profileDetails($id) {
        if(!$id) {
            return $this->errorResponse(['message' => 'Partner Id can\'t be blank.']);
        }

        $partner = Partner::with('User')->find($id);
        
        if(!$partner){
            return $this->errorResponse(['message' => 'No partner found associated with given ID.']);
        }

        $partnerDetails['first_name']               =   $partner->user->first_name;
        $partnerDetails['last_name']                =   $partner->user->last_name;
        $partnerDetails['mobile_country_code']      =   $partner->user->mobile_country_code;
        $partnerDetails['mobile_number']            =   $partner->user->mobile_number;
        $partnerDetails['is_mobile_verified']          =   $partner->user->is_mobile_verified;
        $partnerDetails['email']                    =   $partner->user->email;
        $partnerDetails['is_individual'] = $partner->individual ? true : false;
        $partnerDetails['is_company'] = $partner->company ? true : false;

        return $this->successResponse([
            'profile' => $partnerDetails,
        ]);
    }
}