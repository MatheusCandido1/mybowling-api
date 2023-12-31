<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function index() {
        try {
            $games = Game::with('ball')->ofStatus('COMPLETED')->ofLoggedUser()->get();

            $all_time_average = $games->avg('total_score');
            $current_month_average = $games->whereBetween('game_date', [now()->startOfMonth(), now()->endOfMonth()])->avg('total_score');
            $last_month_average = $games->whereBetween('game_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->avg('total_score');

            $most_used_balls = $games->groupBy('ball_id')->map(function ($item, $key) {
                return [
                    'ball' => [
                        'id' => $item->first()->ball->id,
                        'name' => $item->first()->ball->name,
                        'weight' => $item->first()->ball->weight,
                        'color' => $item->first()->ball->color,
                    ],
                    'total_score' => $item->sum('total_score'),
                    'total_games' => $item->count(),
                ];
            })->sortByDesc('total_games')->values()->all();

            $highest_score = $games->max('total_score') ?? 0;

            $results = DB::table('frames')
            ->join('games', 'frames.game_id', '=', 'games.id') // Join the games table
            ->select('frames.split')
            ->selectRaw('COUNT(*) AS attempted')
            ->selectRaw('SUM(CASE WHEN frames.points = 10 THEN 1 ELSE 0 END) AS converted')
            ->where('frames.is_split', true)
            ->where('games.user_id', auth()->user()->id)
            ->groupBy('frames.split')
            ->get();
            // Organize the results into the desired format
            $splits_converted = [];
            foreach ($results as $row) {
                $splits_converted[] = [
                    'split' => $row->split,
                    'attempted' => (int)$row->attempted,
                    'converted' => (int)$row->converted,
                    'rate' => $row->attempted > 0 ? round($row->converted / $row->attempted * 100) : 0,
                ];
            }

            usort($splits_converted, function ($a, $b) {
                return $b['rate'] - $a['rate'];
            });

            $response = [
                'total_games' => $games->count(),
                'all_time_average' => ceil($all_time_average),
                'current_month_average' => ceil($current_month_average),
                'last_month_average' => ceil($last_month_average),
                'most_used_balls' => $most_used_balls,
                'highest_score' => $highest_score,
                'splits_converted' => $splits_converted,
            ];

            return response()->json([
                'data' => $response
            ], 200);

        } catch(\Exception $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }
}
