<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerRating extends Model
{
    public function partner() {
        return $this->belongsTo('App\Models\Partner');
    }

    public function customer() {
        return $this->belongsTo('App\Models\Customer');
    }
}
