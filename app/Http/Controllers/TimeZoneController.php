<?php

namespace App\Http\Controllers;

use App\Models\TimeZone;
use Illuminate\Http\Request;

class TimeZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response([
            'status' => 'ok',
            'info' => [
                'timeZones' => TimeZone::all()
            ]
        ]
        , 200);
    }
}