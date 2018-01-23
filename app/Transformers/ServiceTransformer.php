<?php
/**
 * Created by PhpStorm.
 * User: sadhya
 * Date: 29/10/17
 * Time: 3:01 PM
 */

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Service;

class ServiceTransformer extends TransformerAbstract
{
    public function transform(Service $service)
    {
        return [
            'id'                    =>      $service->id,
            'name'                  =>      $service->name,
            'sub_category_id'       =>      $service->sub_category_id
        ];
    }
}