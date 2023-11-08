<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;

use App\Http\Resources\Game\GameResource;

use App\Http\Requests\Game\GameStoreRequest;

use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function store(GameStoreRequest $request) {
        try {
            DB::beginTransaction();

            $game = new Game();
            $game->game_date = $request->game_date;
            $game->location_id = $request->location_id;
            $game->user_id = 1;
            $game->ball_id = $request->ball_id;
            $game->total_score = 0;
            $game->status = 'IN_PROGRESS';

            $game->save();

            // Create 10 frames for the game
            for ($i = 1; $i <= 10; $i++) {
                $frame = new \App\Models\Frame();
                $frame->frame_number = $i;
                $frame->first_shot = null;
                $frame->second_shot = null;
                $frame->third_shot = null;
                $frame->split = null;
                $frame->is_split = null;
                $frame->points = 0;
                $frame->score = 0;
                $frame->status = $i == 1 ? 'in_progress' : 'pending';

                $game->frames()->save($frame);
            }

            DB::commit();

            return response()->json([
                'message' => 'Game created'
            ], 201);

        } catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }

    }

    public function index(Request $request)
    {
        try {

            $ball_id = $request->query('ball');
            $location_id = $request->query('location');
            $start_date = $request->query('start_date');
            $end_date = $request->query('end_date');

            $games = Game::with('frames', 'location', 'ball')
                ->ofBall($ball_id)
                ->ofLocation($location_id)
                ->ofStartDate($start_date)
                ->ofEndDate($end_date)
                ->where('status', 'COMPLETED')
                ->orderBy('game_date', 'desc')
                ->get();

            return GameResource::collection($games);

        } catch(\Exception $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Request $request, $game)
    {
        try {
            $game = Game::with('frames', 'location')->find($game);

            if (!$game) {
                throw new \Exception('Game not found');
            }

            return new GameResource($game);

        } catch(\Exception $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }
}
