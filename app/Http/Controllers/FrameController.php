<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Frame;
use App\Models\Game;
use Illuminate\Support\Facades\DB;

class FrameController extends Controller
{
    public function store(Request $request) {
        try {
            DB::beginTransaction();

            $game = Game::find($request->game_id);

            if(!$game) {
                return response()->json([
                    'error_message' => 'Game not found'
                ], 404);
            }

            $frame = new Frame();
            $frame->frame_number = $request->frame_number;
            $frame->first_shot = $request->first_shot;
            $frame->second_shot = $request->second_shot;
            $frame->third_shot = $request->third_shot;
            $frame->split = $request->split;
            $frame->isSplit = $request->isSplit;
            $frame->points = $request->points;
            $frame->score = $request->score;
            $frame->status = $request->status;

            $game->frames()->save($frame);


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
}
