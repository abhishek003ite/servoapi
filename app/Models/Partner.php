<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Location\Coordinate;
use Location\Distance\Vincenty;

class Partner extends Model
{
    public function individual() {
        return $this->hasOne('App\Models\Individual');
    }

    public function company() {
        return $this->hasOne('App\Models\Company');
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function services() {
        return $this->belongsToMany('App\Models\Service', 'partner_services');
    }

    public function address() {
        return $this->belongsTo('App\Models\Address');
    }

    public function ratings() {
        return $this->hasMany('App\Models\PartnerRating');
    }

    public function orders($filter) {
        return $this->hasMany('App\Models\Order');
    }

    public function getFullName() {
        return $this->user->first_name . ' ' . $this->user->last_name;
    }

    /**
     * Funtion to return list of partners based on how far they are from 
     * the given location.
     * 
     * Note: This is a HIGLY inefficient function that goes through ALL
     * the partners. This needs to be optimized once the product starts
     * seeing non-trivial loads.
     * 
     * @param $lat Decimal Latitude of the center
     * @param $lng Decimal Longitude of the center
     * @param $radius Integer Radius of the center
     * @param $max_results Integer Total number of partners to get
     * @todo Sort by distance and apply MAX filter
     */
    public static function getListByDistance($lat, $lng, $radius = 5, $max_results=10) {
        
        $partnersList = [];

        $partners = self::all();

        foreach($partners as $partner) {
            if(!$partner->service_location_lat || !$partner->service_location_long) {
                continue;
            }

            $coordinate1 = new Coordinate($lat, $lng);
            $coordinate2 = new Coordinate($partner->service_location_lat, $partner->service_location_long);

            $distance = $coordinate1->getDistance($coordinate2, new Vincenty()) / 1000; // divide by 1000 to get kilometers

            if ($distance <= $partner->service_radius_km && $distance <= $radius) {
                $partner->distance = $distance;
                $partnersList[] = $partner;
            }
        }

        return $partnersList;
    }
}
