<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\OrderableApi;

class SubCategory extends Model
{
    use OrderableApi;
    protected $fillable = [];

    public function services() {
        return $this->hasMany('App\Models\Service');
    }
}
