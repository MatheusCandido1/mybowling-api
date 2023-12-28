<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\Auth\LoggedUserResource;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function first_access() {
        try {

            $user = auth()->user();

            $user->profile->first_access = false;
            $user->profile->save();

            return response()->json([
                'data' => new LoggedUserResource($user),
                'message' => 'Welcome to MyBowling App!'
            ], 202);

        } catch(\Exception $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }


}
