<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Library\Common;
use App\Library\FileManager;
use App\Models\Individual;
use App\Models\Partner;
use Illuminate\Http\Request;
use Validator;

class PartnerIndividualController extends Controller
{
    public function getIndividual(Request $request, $id){
        $partner = Partner::find($id);

        $individual = $partner->individual;

        $responseData = [];

        if($individual == null) {
            return $this->successResponse($responseData);
        }

        $responseData['account_holder_name'] = $individual->account_holder_name;
        $responseData['bank_account_number'] = $individual->bank_account_number;
        $responseData['ifsc_code'] = $individual->ifsc_code;
        $responseData['pan_number'] = $individual->pan_number;
        $responseData['gst_number'] = $individual->gst_number;
        $responseData['aadhaar_number'] = $individual->aadhaar_number;

        if($individual->aadhaar_scan_front_file) {
            $responseData['front_scan'] = env('FILE_STORAGE_URL') . $individual->aadhaar_scan_front_file;
        }

        if($individual->aadhaar_scan_back_file) {
            $responseData['back_scan'] = env('FILE_STORAGE_URL') . $individual->aadhaar_scan_back_file;
        }

        return $this->successResponse($responseData);

    }

    //Storing details of Partner with Address
    public function registerIndividual(Request $request, $id) {
        $partner = Partner::find($id);

        // $validator = Validator::make($request->all(), [
        //     'account_holder_name'   =>  'required|max:200',
        //     'bank_account_number'   =>  'required|max:30',
        //     'ifsc_code'             =>  'required|max:20',
        //     'aadhaar_number'   =>  'required|max:200',
        // ], Common::getValidationMessageFormat());

        // if($validator->fails()) {
        //     $errors = $validator->errors();
        //     $first_error = $errors->all()[0];

        //     return $this->errorResponse(['message'=> $first_error]);
        // }

        $agree1 = $request->agree_best_knowledge;
        $agree2 = $request->agree_terms_conditions;

        if(
            !in_array($agree1, ["yes", "no"]) 
            || is_null($agree1) 
            || $agree1 == "no" 
            || !in_array($agree2, ["yes", "no"]) 
            || is_null($agree2) 
            || $agree2 == "no") {
                return $this->errorResponse(['message' => 'You must agree to the terms and conditions.'
                ]);
        }
        
        if($partner->individual) {
            $individual = $partner->individual;
        } else {
            $individual = new Individual();
        }

        $individual->account_holder_name = $request->account_holder_name;
        $individual->bank_account_number = $request->bank_account_number;
        $individual->ifsc_code = $request->ifsc_code;
        $individual->pan_number = $request->pan_number;
        $individual->gst_number = $request->gst_number;
        $individual->aadhaar_number = $request->aadhaar_number;

        if($request->hasFile('aadhaar_scan_front_file')) {
            $individual->aadhaar_scan_front_file = FileManager::store($request->aadhaar_scan_front_file);
        }
        
        if($request->hasFile('aadhaar_scan_back_file')) {
            $individual->aadhaar_scan_back_file = FileManager::store($request->aadhaar_scan_back_file);
        }
        $individual->agree_best_knowledge = $request->agree_best_knowledge;
        $individual->agree_terms_conditions = $request->agree_terms_conditions;
        $individual->partner_id = $id;

        $individual->save();
        $partner->save();

        return $this->successResponse([
            'images'        =>  [
                'aadhaar_front'  =>  env('FILE_STORAGE_URL') . $individual->aadhaar_scan_front_file,
                'aadhaar_back'   =>  env('FILE_STORAGE_URL') . $individual->aadhaar_scan_back_file
            ],
        ]);
    }
}