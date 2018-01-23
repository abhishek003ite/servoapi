<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public static function isStatusValid($status) {
            return in_array($status, [
                'created',
                'paid',
                'accepted',
                'declined',
                'cancelled',
                'completed']
            );
    }

    public static function getDefaultStatus() {
        return 'created';
    }

    public function items() {
        return $this->hasMany('App\Models\OrderItem');
    }
    
    public function customer() {
        return $this->belongsTo('App\Models\Customer');
    }

    public function partner() {
        return $this->belongsTo('App\Models\Partner');
    }

    public function getCreatedDate(){
        return $this->created_at->toDateTimeString();
    }
}
