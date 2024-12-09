<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RetrospectController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function index()
    {
        try {

            $games = Game::with('ball','location','frames')
                ->ofStatus('COMPLETED')
                ->ofLoggedUser()
                ->whereYear('game_date', 2024)
                ->get();

            $response = [];

            $response['total_games'] = $games->count();

            $response['total_locations'] = $games->groupBy('location_id')->count();

            $response['total_balls'] = $games->groupBy('ball_id')->count();

            // Get the 3 games with the highest score
            $response['highest_score'] = $games->sortByDesc('total_score')->values()->take(3);


            return response()->json([
                'data' => $response
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
