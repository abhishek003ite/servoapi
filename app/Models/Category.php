<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\OrderableApi;

class Category extends Model
{
    use OrderableApi;

    public function subCategories() {
        return $this->hasMany('App\Models\SubCategory');
    }
}
