<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ball;
use App\Http\Resources\Ball\BallResource;

use App\Http\Requests\Ball\BallStoreRequest;
use App\Http\Requests\Ball\BallUpdateRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BallController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function store(BallStoreRequest $request) {
        try {
            DB::beginTransaction();

            $ball = new Ball();

            $ball->name = $request->name;
            $ball->weight = $request->weight;
            $ball->color = $request->color;

            $ball->save();

            auth()->user()->balls()->save($ball);

            DB::commit();

            return response()->json([
                'data' => new BallResource($ball),
                'message' => 'Ball created'
            ], 201);

        } catch(\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(BallUpdateRequest $request, $id) {
        try {
            DB::beginTransaction();

            $ball = Ball::ofLoggedUser()->find($id);

            if(!$ball) {
                return response()->json([
                    'error_message' => 'Ball not found'
                ], 404);
            }

            $ball->name = $request->name;
            $ball->weight = $request->weight;
            $ball->color = $request->color;

            $ball->save();

            DB::commit();

            return response()->json([
                'data' => new BallResource($ball),
                'message' => 'Ball updated'
            ], 201);

        } catch(\Exception $e) {
            DB::rollBack();

            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function index() {
        try {
            return BallResource::collection(Ball::ofLoggedUser()->get());

        } catch(\Exception $e) {
            return response()->json([
            'error_message' => $e->getMessage(),
            ], 500);
        }
   }

   public function destroy($id) {
    try {
        DB::beginTransaction();

        $ball = Ball::ofLoggedUser()->find($id);

        if(!$ball) {
            return response()->json([
                'error_message' => 'Ball not found'
            ], 404);
        }

        $ball->delete();

        DB::commit();

        return response()->json([
            'message' => 'Ball deleted'
        ], 202);

    } catch(\Exception $e) {
        DB::rollBack();

        return response()->json([
            'error_message' => $e->getMessage(),
        ], 500);
    }
}
}
