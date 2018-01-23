<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\OrderableApi;

class Company extends Model
{
    use OrderableApi;
    protected $fillable = [];
}
