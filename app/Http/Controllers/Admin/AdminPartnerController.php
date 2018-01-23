<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Models\User;
use App\Models\Partner;
use App\Models\PartnerService;
use App\Models\Service;
use App\Models\Company;
use App\Models\Individual;
class AdminPartnerController extends Controller
{
     /**
     * Partner Details
     */
    public function partners(Request $request, $admin_id) {
        if(!$admin_id){
            return $this->errorResponse(['message' => 'Admin Id can\'t be blank.']);
        }

        $admin = User::find($admin_id);
        if( !$admin ){
            return $this->errorResponse(['message' => 'No admin found associated with given ID.']);
        }

        $partners = Partner::all();
        $final_partner = [];

        foreach($partners as $partner) {
            $partner_id = $partner->id;
            $user_id = $partner->user->id;

            $partner_details = User::where('id', $user_id)->where('is_partner', 'yes')->get(['first_name', 'last_name', 'mobile_country_code', 'mobile_number', 'email'])[0];
            $partner_services = PartnerService::where('partner_id', $partner_id)->get(['service_id']);

            $services = [];

            foreach($partner_services as $service) {
                $services[] = Service::where('id', $service->service_id)->get(['id', 'name'])[0];                
            }
            
            $final_partner[] = [
                'id' => $partner_id,
                'personal_details' => $partner_details, 
                'services' => $services
            ];
        }

        return $this->successResponse([
            'partners' =>  $final_partner,
        ]);
    }
    public function partnerDetails($admin_id, $partner_id) {
        if(!$admin_id){
            return $this->errorResponse(['message' => 'Admin Id can\'t be blank.']);
        }
        $admin = User::find($admin_id);
        if( !$admin ){
            return $this->errorResponse(['message' => 'No admin found associated with given ID.']);
        }
        if(!$partner_id){
            return $this->errorResponse(['message' => 'Partner Id can\'t be blank.']);
        }
        $partner = Partner::find($partner_id);
        if( !$partner ){
            return $this->errorResponse(['message' => 'No partner found associated with given ID.']);
        }
        $partner = Partner::where('id', $partner_id)->get()->first();
        $user_id = $partner->user_id;
        $partner_services = PartnerService::where('partner_id', $partner_id)->get(['service_id', 'visitation_price', 'service_price', 'details']);
        $pricing = [];        
        foreach($partner_services as $service) {
            $services = Service::where('id', $service->service_id)->get(['id', 'name'])[0];  
            $service_id = $services->id;              
            $service_name = $services->name;
            $service_visitation = $service->visitation_price;
            $service_charge = $service->service_price;              
            $service_details = $service->details;
            $pricing[] = ['service_id' => $service_id, 
                'service_name' => $service_name, 
                'visitation_price' => $service_visitation, 
                'service_price' => $service_charge, 
                'details' => $service_details
            ];          
        }
        $partner_details['location'] = ['lat' => $partner->service_location_lat, 'long' => $partner->service_location_long];
        $partner_details['service_radius_km'] = $partner->service_radius_km;
        $partner_details['workdays'] = [
            'sun' => $partner->works_sundays, 
            'sat' => $partner->works_sundays,
            'mon' => $partner->works_mondays,
            'tue' => $partner->works_tuesdays,
            'wed' => $partner->works_wednesdays,
            'thu' => $partner->works_thursdays,
            'fri' => $partner->works_fridays
        ];
        $partner_details['timings'] = [
            'start' => $partner->work_timings_start, 
            'end' => $partner->work_timings_end,
        ];
        $partner_details['pricing'] = $pricing; 
        
        $user = User::where('id', $user_id)->get(['first_name', 'last_name']);
        
        $company_details = false;
        $invidual_details = false;

        if($partner->company) {
            $company_details = Company::where('partner_id',$partner_id)->get(['id', 'account_holder', 'bank_account_number', 'ifsc_code', 'pan_number', 'gst_number', 'director_name_1', 'director_pan_1', 'director_name_2', 'director_pan_2', 'partner_id'])->first();
        }
        
        if($partner->individual) {
            $invidual_details = Individual::where('partner_id', $partner_id)->get(['id', 'account_holder_name', 'bank_account_number', 'ifsc_code', 'pan_number', 'gst_number', 'aadhaar_number', 'partner_id'])->first();
        }   

        return $this->successResponse([
            'name' => $user,
            'service_details' => $partner_details,
            'company_details' => $company_details,
            'individual_details' => $invidual_details
        ]);
    }
}
