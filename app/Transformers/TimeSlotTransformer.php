<?php
/**
 * Created by PhpStorm.
 * User: sadhya
 * Date: 29/10/17
 * Time: 3:02 PM
 */

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\TimeSlot;

class TimeSlotTransformer extends TransformerAbstract
{
    public function transform(TimeSlot $timeSlot)
    {
        return [
            //
        ];
    }
}