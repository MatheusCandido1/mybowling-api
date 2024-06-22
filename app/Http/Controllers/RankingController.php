<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Carbon\Carbon;

use App\Http\Resources\Ranking\RankingResource;

class RankingController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function index(Request $request) {
        try {
            $period = $request->period;

            $currentWeek = [
                'start' => Carbon::now()->startOfWeek(),
                'end' => Carbon::now()->endOfWeek(),
            ];

            $currentMonth = [
                'start' => Carbon::now()->startOfMonth(),
                'end' => Carbon::now()->endOfMonth()->subDay(),
            ];

            $ranking = Game::with('ball', 'location', 'user', 'frames')
            ->ofStatus('COMPLETED')
            ->whereBetween('game_date',
                $period === 'Week' ?
                $currentWeek :
                $currentMonth
            )
            ->get()
            ->sortByDesc('total_score')
            ->values()
            ->map(function ($game, $index) {
                $game->rank = $index + 1;
                return $game;
            });;



            return response()->json([
                'data' => RankingResource::collection($ranking->values()->take(10)),
            ], 200);

        } catch(\Exception $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }
}
