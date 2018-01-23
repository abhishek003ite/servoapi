<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        return response([
            'status' => 'ok',
            'info' => [
                'services' => Service::all()
            ]
        ]
        , 200);
    }
}
