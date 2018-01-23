<?php

namespace App\Http\Controllers\Partner;

use App\Models\Partner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PartnerService;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Validator;

class PartnerPricingController extends Controller
{
    public function getPricing($id) {
        $final_service = [];
        $service_list = DB::table('partner_services')
        ->where('partner_services.partner_id', '=', $id)
        ->join('services', 'partner_services.service_id', '=', 'services.id')
        ->select('partner_services.*', 'services.name')
        ->get();
        
        foreach($service_list as $list) {
            $final_service[] = [
                'id' => $list->service_id, 
                'service_name' => $list->name, 
                'service_price' => $list->service_price, 
                'details' => $list->details
            ];
        }
        
        $partner = Partner::find($id);

        return $this->successResponse([
            'visitation_charges' => $partner->visitation_charges, 
            'services' => $final_service,
        ]);
        
    }

    public function updatePricing(Request $request, $id){
        $partner = Partner::find($id);

        $validator = Validator::make($request->all(), [
            'pricing'    =>  'array',
            'visitation_charges' => 'required|numeric'
        ]);

        if($validator->fails()) {
            $errors = $validator->errors();
            $first_error = $errors->all()[0];

            return $this->errorResponse(['message'=> $first_error]);
        }

        $partner->visitation_charges = $request->visitation_charges;
        $partner->save();

        for($i = 0; $i < count($request->pricing); $i++){
            $partner_service = PartnerService::where('partner_id', $id)->where('service_id', $request->pricing[$i]['id'])->first();
            $partner_service->service_price = $request->pricing[$i]['service_price'];
            $partner_service->details = $request->pricing[$i]['details'];

            $partner_service->save();
        }

        return $this->successResponse([]);
    }

    public function getServicePricing($id) {
    }
}
