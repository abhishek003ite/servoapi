<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\OrderableApi;

class PartnerService extends Model
{
    use OrderableApi;
    protected $fillable = ['partner_id', 'service_id'];

    public function partner() {
        return $this->belongsTo('App\Models\Partner');
    }

    public function service() {
        return $this->belongsTo('App\Models\Service');
    }
}
