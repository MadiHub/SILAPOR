<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\LocationService;

class LocationController extends Controller
{
    public function polygon()
    {
        $service = new LocationService();

        return response()->json([
            "coordinates" => [
                $service->getPolygon()
            ]
        ]);
    }
}