<?php
/**
 * Created by PhpStorm.
 * User: sadhya
 * Date: 29/10/17
 * Time: 3:00 PM
 */

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Company;

class CompanyTransformer extends TransformerAbstract
{
    public function transform(Company $company)
    {
        //Return Transform
        return [
            'success'                   =>  200,
            'message'                   =>  'Company Created Successfully',
            'id'                        =>  $company->id,
            'name'                      =>  $company->name,
            'agree_best_knowledge'      =>  $company->agree_best_knowledge,
            'agree_terms_conditions'    =>  $company->agree_terms_conditions
        ];
    }
}