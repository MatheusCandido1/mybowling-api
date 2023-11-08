<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ball;
use App\Http\Resources\Ball\BallResource;

class BallController extends Controller
{
    public function index(Request $request) {
        try {
            return BallResource::collection(Ball::all());

        } catch(\Exception $e) {
            return response()->json([
            'error_message' => $e->getMessage(),
            ], 500);
        }
   }
}
