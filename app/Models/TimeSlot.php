<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\OrderableApi;

class TimeSlot extends Model
{
    use OrderableApi;
    protected $fillable = [];
}
