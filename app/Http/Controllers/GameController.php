<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Frame;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\Game\GameResource;

use App\Http\Requests\Game\GameStoreRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;


class GameController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function store(GameStoreRequest $request) {
        try {
            DB::beginTransaction();

            $game = new Game();
            $game->game_date = $request->game_date;
            $game->location_id = $request->location_id;
            $game->user_id = auth()->user()->id;
            $game->ball_id = $request->ball_id ?? null;
            $game->total_score = 0;
            $game->group_id = $request->group_id ?? null;
            $game->status = 'IN_PROGRESS';

            $game->save();

            // Create 10 frames for the game
            for ($i = 1; $i <= 10; $i++) {
                $frame = new Frame();
                $frame->frame_number = $i;
                $frame->first_shot = null;
                $frame->second_shot = null;
                $frame->third_shot = null;
                $frame->pins = null;
                $frame->is_split = null;
                $frame->points = 0;
                $frame->score = 0;
                $frame->status = $i == 1 ? 'in_progress' : 'pending';

                $game->frames()->save($frame);
            }

            DB::commit();

            return response()->json([
                'data' => new GameResource($game),
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
                ->ofLoggedUser()
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

    public function update(Request $request, $game) {
        try {

            $existingGame = Game::find($game);

            if(!$existingGame) {
                return response()->json([
                    'error' => 'Game not found'
                ], 404);
            }


            $existingGame->status = $request->status;
            $existingGame->total_score = $request->total_score;
            $existingGame->save();


            // Update frames
            $framesArray = $request->input('frames');


            foreach($framesArray as $frameData) {
                $existingFrame = Frame::find($frameData['id']);

                if(!$existingFrame) {
                    return response()->json([
                        'error' => 'Frame not found'
                    ], 404);
                }

                $existingFrame->first_shot = $frameData['first_shot'];
                $existingFrame->second_shot = $frameData['second_shot'];
                $existingFrame->third_shot = $frameData['third_shot'];
                $existingFrame->pins = $frameData['pins'];
                $existingFrame->is_split = $frameData['is_split'];
                $existingFrame->points = $frameData['points'];
                $existingFrame->score = $frameData['score'];
                $existingFrame->status = $frameData['status'];
                $existingFrame->save();
            }








            return response()->json([
                'message' => 'Game updated'
            ], 202);


        } catch(\Exception $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }


    public function ongoing() {
        try {

            $games = Game::with('frames', 'location', 'ball')
            ->ofStatus('IN_PROGRESS')
            ->ofLoggedUser()
            ->get();

            if (!$games) {
                return response()->json([
                    'message' => 'No ongoing game'
                ], 200);
            }

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
