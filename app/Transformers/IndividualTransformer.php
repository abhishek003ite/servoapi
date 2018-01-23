<?php
/**
 * Created by PhpStorm.
 * User: sadhya
 * Date: 29/10/17
 * Time: 3:00 PM
 */

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Individual;

class IndividualTransformer extends TransformerAbstract
{
    public function transform(Individual $individual)
    {
        return [
            'status'                    =>      200,
            'message'                   =>      'Individual Account Created Successfully.',
            'id'                        =>      $individual->id,
            'bank_account_name'         =>      $individual->bank_account_name,
            'bank_account_num'          =>      $individual->bank_account_num,
            'ifsc_code'                 =>      $individual->ifsc_code,
            'pan_num'                   =>      $individual->pan_num,
            'gst_num'                   =>      $individual->gst_num,
            'aadhaar_num'               =>      $individual->aadhaar_num,
            'profile_photo_file'        =>      $individual->profile_photo_file,
            'adhaar_scan_front_file'    =>      $individual->adhaar_scan_front_file,
            'adhaar_scan_back_file'     =>      $individual->adhaar_scan_back_file,
            'agree_best_knowledge'      =>      $individual->agree_best_knowledge,
            'agree_terms_conditions'    =>      $individual->agree_terms_conditions,
            'address_id'                =>      $individual->address_id
        ];
    }
}