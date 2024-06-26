<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Game;
use App\Http\Resources\Location\LocationResource;

class LocationController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

   public function index(Request $request) {
        try {
            $city = $request->city;
            $state = $request->state;

            if(isset($city) || isset($state)) {
                $locations = Location::where('city', $city)
                ->where('state', $state)
                ->get();

                return LocationResource::collection($locations);
            } else {
                $games = Game::with('location')
                ->ofLoggedUser()
                ->get()
                ->map(function ($game) {
                    return [
                        'id' => $game->location->id,
                        'name' => $game->location->name,
                    ];
                })->unique()->values()->all();

                return response()->json([
                    'data' => $games
                ], 200);
            }



            /*


            if(isset($city) || isset($state)) {
                $locations = Location::where('city', $city)
                ->where('state', $state)
                ->get();

                return LocationResource::collection($locations);
            }

            $locations = Game::with('location')
                ->ofLoggedUser()
                ->get();
                /*
                ->map(function ($game) {
                    return [
                        'id' => $game->location->id,
                        'name' => $game->location->name,
                    ];
                })->unique()->values()->all();
                   return response()->json([
                'data' => $locations
            ], 200);
                */



        } catch(\Exception $e) {
            return response()->json([
            'error_message' => $e->getMessage(),
            ], 500);
        }
   }
}
