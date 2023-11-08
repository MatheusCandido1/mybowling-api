<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index() {
        try {
            $games = Game::with('ball')->ofStatus('COMPLETED')->loggedUser(1)->get();

            $all_time_average = $games->avg('total_score');
            $current_month_average = $games->whereBetween('game_date', [now()->startOfMonth(), now()->endOfMonth()])->avg('total_score');
            $last_month_average = $games->whereBetween('game_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->avg('total_score');

            $most_used_balls = $games->groupBy('ball_id')->map(function ($item, $key) {
                return [
                    'ball' => $item->first()->ball,
                    'total_score' => $item->sum('total_score'),
                    'total_games' => $item->count(),
                ];
            })->sortByDesc('total_games')->values()->all();

            $highest_score = $games->max('total_score');

            $results = DB::table('frames')
            ->select('split')
            ->selectRaw('COUNT(*) AS attempted')
            ->selectRaw('SUM(CASE WHEN points = 10 THEN 1 ELSE 0 END) AS converted')
            ->where('is_split', true)
            ->groupBy('split')
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
