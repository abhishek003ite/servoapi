<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Service;
use App\Models\Partner;
use App\Models\PartnerService;

class CustomerOrderController extends Controller
{
    public function allCategories() {
        $all_categories = Category::all();        
        $categories = [];

        foreach ($all_categories as $cat) {
            $sub_categories = [];
            $sub_cats = $cat->subCategories;

            foreach($sub_cats as $sub) {
                $sub_categories[] = [
                    'id' => $sub->id,
                    'name' => $sub->name,
                    'description' => $sub->description,
                    'img_small' => env('FILE_STORAGE_URL') . $sub->img_small,
                    'img_large' => env('FILE_STORAGE_URL') . $sub->img_large, 
                ];
            }

            $categories[] = [
                'id' => $cat->id,
                'name' => $cat->name,
                'description' => $cat->description,
                'img_small' => env('FILE_STORAGE_URL') . $cat->img_small,
                'img_large' => env('FILE_STORAGE_URL') . $cat->img_large,
                'sub_categories' => $sub_categories,
            ];
        }

        return $this->successResponse([
            'categories' => $categories
        ]);
    }

    public function allServices() {
        $all_services = Service::all();
        $services = [];

        foreach($all_services as $serv) {
            $services[] = [
                'id' => $serv->id,
                'name' => $serv->name,
                'description' => $serv->description,
                'img_small' => env('FILE_STORAGE_URL') . $serv->img_small,
                'img_large' => env('FILE_STORAGE_URL') . $serv->img_large, 
            ];
        }

        return $this->successResponse([
            'services' => $services
        ]);
    }

    public function getPartnersByCategory(Request $request, $id) {
        $customerLocation = $request->location;

        $category = Category::find($id);

        if(!$category) {
            return $this->errorResponse([
                'message' => 'Invalid cateogry.'
            ]);
        }

        /**
         * Logic for getting partners:
         * 1) Find all sub-categories
         * 2) Iterate through sub-categories and find all services
         * 3) Iterate through all services and create a unique array
         */

        $partnersArray = []; // this will be made unique later

        $subCategories = $category->subCategories;

        foreach ($subCategories as $subCategory) {
            $services = $subCategory->services;

            foreach ($services as $service) {
                $partners = $service->partners;

                foreach($partners as $partner) {
                    $partnersArray[] = $partner;
                }
            }
        }

        // $partnersArray now contains all the partners
        // we just need to weed out the unique ones
        $uniquePartners = [];
        $usedIds = [];

        foreach($partnersArray as $partner) {
            if(!in_array($partner->id, $usedIds)) {
                $usedIds[] = $partner->id;               
                $uniquePartners[] = [
                    'id' => $partner->id,
                    'name' => $partner->user->getFullName(),
                    'profile_photo' => env('FILE_STORAGE_URL') . $partner->profile_photo_file,
                    'about' => $partner->about
                ];
            }
        }

        return $this->successResponse([
            'partners' => $uniquePartners
        ]);
    }

    public function getPartnersByService(Request $request, $id) {
        $customerLocation = $request->location;

        $service = Service::find($id);

        if(!$service) {
            return $this->errorResponse([
                'message' => 'Invalid service.'
            ]);
        }

        $partners = $service->partners;

        $finalArray = [];

        foreach ($partners as $partner) {
            $finalArray[] = [
                'id' => $partner->id,
                'name' => $partner->user->getFullName(),
                'profile_photo' => env('FILE_STORAGE_URL') . $partner->profile_photo_file,
                'about' => $partner->about
            ];
        }

        /**
         * Sort by distance if we have customer's location. 
         * This check is needed because the user might be browsing without
         * logging in.
         */
        
        if($customerLocation) {

        }

        return $this->successResponse([
            'partners' => $finalArray
        ]);
    }

    public function getPartnerServices($id) {
        $partner = Partner::find($id);

        if(!$partner) {
            return $this->errorResponse([
                'message' => 'Invalid partner id.'
            ]);
        }

        $services = $partner->services;

        $servicesWithPricing = [];

        foreach($services as $service) {
            $partnerService = PartnerService::where('partner_id', $partner->id)->where('service_id', $service->id)->get()->first();

            $servicesWithPricing[] = [
                'id' => $partnerService->id,
                'name' => $service->name,
                'charges' => round($partnerService->service_price * 
                                (1 + env('SERVO_SHARE_SERVICE_CHARGES')/100), 2),
                'details' => $partnerService->details
            ];
        }

        return $this->successResponse([
            'visitation_charges' => round($partner->visitation_charges 
                + env('SERVO_SHARE_VISITATION_CHARGES'), 2),
            'services' => $servicesWithPricing
        ]);
    }
}