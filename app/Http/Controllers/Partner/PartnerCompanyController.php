<?php

namespace App\Http\Controllers\Partner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Models\Partner;
use App\Library\Common;
use App\Library\FileManager;
use Validator;


class PartnerCompanyController extends Controller
{
    public function getCompany(Request $request, $id){
        $partner = Partner::find($id);

        $company = $partner->company;       

        $responseData = [];
        
        if($company == null) {
            return $this->successResponse($responseData);
        }

        $responseData['account_name'] = $company->account_holder;
        $responseData['account_number'] = $company->bank_account_number;
        $responseData['ifsc'] = $company->ifsc_code;
        $responseData['pan'] = $company->pan_number;
        $responseData['gst'] = $company->gst_number;
        $responseData['dir1_name'] = $company->director_name_1;
        $responseData['dir1_pan'] = $company->director_pan_1;
        $responseData['dir2_name'] = $company->director_name_2;
        $responseData['dir2_pan'] = $company->director_pan_2;

        if($company->certificate_incorporation) {
            $responseData['cert_inc'] = env('FILE_STORAGE_URL') . $company->certificate_incorporation;
        }

        return $this->successResponse($responseData);
    }

    public function registerCompany(Request $request, $id) {
        $partner = Partner::find($id);

        // $validator = Validator::make($request->all(), [
        //     'account_holder'   =>  'required|max:200',
        //     'bank_account_number'   =>  'required|max:30',
        //     'ifsc_code'             =>  'required|max:20',
        //     'director_name_1'       =>  'required|max:50',
        //     'director_pan_1'        =>  'required|max:12'
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
        
        if($partner->company) {
            $company = $partner->company;
        } else {
            $company = new Company();
        }

        $company->account_holder = $request->account_holder;
        $company->bank_account_number = $request->bank_account_number;
        $company->ifsc_code = $request->ifsc_code;
        $company->pan_number = $request->pan_number;
        $company->gst_number = $request->gst_number;
        $company->director_name_1 = $request->director_name_1;
        $company->director_pan_1 = $request->director_pan_1;
        $company->director_name_2 = $request->director_name_2;
        $company->director_pan_2 = $request->director_pan_2;

        if($request->hasFile('certificate_incorporation')) {
            $company->certificate_incorporation = FileManager::store($request->certificate_incorporation);
        }
    
        $company->agree_best_knowledge = $request->agree_best_knowledge;
        $company->agree_terms_conditions = $request->agree_terms_conditions;
        $company->partner_id = $id;

        $company->save();
        $partner->save();
        
        return $this->successResponse([
            'images'        =>  [
                'certificate_incorporation'  =>  env('FILE_STORAGE_URL') . $company->certificate_incorporation,
            ],
        ]);
    }
}
