<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Http\Resources\Location\LocationResource;

class LocationController extends Controller
{
   public function index(Request $request) {
        try {
            return LocationResource::collection(Location::all());

        } catch(\Exception $e) {
            return response()->json([
            'error_message' => $e->getMessage(),
            ], 500);
        }
   }
}
