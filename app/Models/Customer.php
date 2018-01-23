<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function address() {
        return $this->belongsTo('App\Models\Address');
    }

    public function orders() {
        return $this->hasMany('App\Models\Order');
    }

    public function cart() {
        return $this->hasOne('App\Models\Cart');
    }
}
