<?php

namespace App\Http\Controllers\Partner;

use App\models\AddService;
use App\Models\Partner;
use App\Models\PartnerService;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Validator;

use App\Library\Common;
use App\Http\Controllers\Controller;

class PartnerServiceController extends Controller
{
    public function addService(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'service_name'   => 'required'
        ]);

        if($validator->fails()) {
            $errors = $validator->errors();
            $first_error = $errors->all()[0];

            return $this->errorResponse(['message'=> $first_error]);
        }

        $addService = new AddService();

        $addService->service_name = $request->service_name;
        $addService->partner_id = $id;

        $addService->save();

        $partner = Partner::find($id);

        $user_id = $partner->user_id;

        $users = User::find($user_id);

        $partnerName = $users->first_name." ".$users->last_name;

        $data = array('name' => $partnerName);

        Mail::send('email.addServiceRequest', $data, function($message) use ($data){
            $message->subject('New Service Request By Partner.');
            $message->to(env('ADMIN_MAIL'));
        });

        return $this->successResponse([
            'serviceId' =>  $addService->id
        ]);
    }

    public function submitServices(Request $request, $id) {
        $partner = Partner::find($id);
        $validator = Validator::make($request->all(), [
            'services'    =>  'array',
        ]);

        if($validator->fails()) {
            $errors = $validator->errors();
            $first_error = $errors->all()[0];
            return $this->errorResponse(['message'=> $first_error]);
        }

        PartnerService::whereNotIn('service_id', $request->services)->where('partner_id', $id)->delete();
    
        for($i = 0; $i < count($request->services); $i++) {
            $partnerService = PartnerService::where('partner_id', $id)->where('service_id', $request->services[$i])->first();
            if(!$partnerService) {
                $partnerService = new PartnerService();
                $partnerService->partner_id = $id;
                $partnerService->service_id = $request->services[$i];
                $partnerService->save();
            }
        }

        $partner->works_mondays = $request->works_mondays;
        $partner->works_tuesdays = $request->works_tuesdays;
        $partner->works_wednesdays = $request->works_wednesdays;
        $partner->works_thursdays = $request->works_thursdays;
        $partner->works_fridays = $request->works_fridays;
        $partner->works_saturdays = $request->works_saturdays;
        $partner->works_sundays = $request->works_sundays;

        $partner->work_timings_start = $request->work_timings_start;
        $partner->work_timings_end = $request->work_timings_end;

        $partner->service_location_lat = $request->service_location_lat;
        $partner->service_location_long = $request->service_location_long;
        $partner->service_radius_km = $request->service_radius_km;

        $partner->profile_completed = 'yes';
        $partner->save();

        return $this->successResponse();

    }

    public function getServiceList($partner_id) {
        $services = Service::all();
        $partner_services = PartnerService::where('partner_id', $partner_id)->get(['service_id']);
        $partner_service = [];
        $final_service = [];

        foreach($partner_services as $list) {
            $partner_service[] = $list->toArray();
        }

        foreach($services as $service) {
            if(in_array($service->id, array_flatten($partner_service))) {
                $final_service[] = ['display_name' => $service->name, 'id' => $service->id, 'is_offered' => true];
            }
            else {
                $final_service[] = ['display_name' => $service->name, 'id' => $service->id, 'is_offered' => false];
            }            
        }

        $partner = Partner::find($partner_id);
        $workdays = [];

        $workdays['mon'] = $partner->works_mondays == 'yes' ? true : false;
        $workdays['tue'] = $partner->works_tuesdays == 'yes' ? true : false;
        $workdays['wed'] = $partner->works_wednesdays == 'yes' ? true : false;
        $workdays['thu'] = $partner->works_thursdays == 'yes' ? true : false;
        $workdays['fri'] = $partner->works_fridays == 'yes' ? true : false;
        $workdays['sat'] = $partner->works_saturdays == 'yes' ? true : false;
        $workdays['sun'] = $partner->works_sundays == 'yes' ? true : false;


        return $this->successResponse([
            'services' => $final_service,
            'workDays' => $workdays,
            'serviceRadius' => (int)$partner->service_radius_km,
            'serviceLocation' => [
                'lat' => $partner->service_location_lat,
                'lng' => $partner->service_location_long
            ],
            'workTimings' => [
                'start' => $partner->work_timings_start,
                'end' => $partner->work_timings_end
            ]
        ]);
    }
}
