<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public static function validStatusValues() {
        return [
            'pending',
            'completed',
            'failed',
        ];
    }

    public static function getDefaultStatus() {
        return 'pending';
    }

    public function order() {
        return $this->belongsTo('App\Models\Order');
    }
}
