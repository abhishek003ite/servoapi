<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\OrderableApi;

class Service extends Model
{
    use OrderableApi;
    protected $fillable = [];

    public function partners() {
        return $this->belongsToMany('App\Models\Partner', 'partner_services');
    }
}
