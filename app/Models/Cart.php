<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public function items() {
        return $this->hasMany('App\Models\CartItem');
    }

    public function partner() {
        return $this->belongsTo('app\Models\Partner');
    }

    public function customer() {
        return $this->belongsTo('app\Models\Customer');
    }
}
