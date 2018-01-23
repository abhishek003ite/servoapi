<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function getTaxRates() {
        return $this->successResponse([
            'taxes' => [
                'gst' => env('GST', 18),
                'conv' => env('CONV_CHARGE', 6),
            ]
        ]); 
    }
}
