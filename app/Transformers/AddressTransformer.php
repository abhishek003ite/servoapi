<?php
/**
 * Created by PhpStorm.
 * User: sadhya
 * Date: 29/10/17
 * Time: 2:11 PM
 */

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Address;

class AddressTransformer extends TransformerAbstract
{
    public function transform(Address $address)
    {
        //Return transform

        return [
            'id'                =>  $address->id,
            'building_num'      =>  $address->building_num,
            'street_address'    =>  $address->street_address,
            'region'            =>  $address->region,
            'city'              =>  $address->city,
            'state'             =>  $address->state,
            'pincode'           =>  $address->pincode,
            'lat'               =>  $address->lat,
            'long'              =>  $address->long,
        ];
    }
}