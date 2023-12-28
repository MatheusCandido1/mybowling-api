<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ball;
use App\Http\Resources\Ball\BallResource;

use App\Http\Requests\Ball\BallStoreRequest;

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

    public function update() {

    }

    public function index(Request $request) {
        try {
            return BallResource::collection(Ball::ofLoggedUser()->get());

        } catch(\Exception $e) {
            return response()->json([
            'error_message' => $e->getMessage(),
            ], 500);
        }
   }
}
