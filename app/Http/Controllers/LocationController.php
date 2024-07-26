<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\Game;

use App\Http\Requests\Location\LocationStoreRequest;

use App\Http\Resources\Location\LocationResource;

use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function store(LocationStoreRequest $request) {
        try {

            DB::beginTransaction();

            $location = new Location();
            $location->name = $request->name;
            $location->city = $request->city;
            $location->state = $request->state;
            $location->user_id = auth()->user()->id;
            $location->active = true;

            $location->save();

            DB::commit();

            return response()->json([
                'data' => 'Location created',
            ], 200);


        } catch(\Exception $e) {
            DB::rollBack();

            return response()->json([
            'error_message' => $e->getMessage(),
            ], 500);
        }
    }


   public function index(Request $request) {
        try {
            $city = $request->city;
            $state = $request->state;

            if(isset($city) && isset($state)) {
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

        } catch(\Exception $e) {
            return response()->json([
            'error_message' => $e->getMessage(),
            ], 500);
        }
   }
}
