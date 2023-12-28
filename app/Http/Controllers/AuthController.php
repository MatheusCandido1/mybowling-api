<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use App\Http\Resources\Auth\LoggedUserResource;

use App\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(LoginRequest $request) {

        $user = User::with('profile')->where('email', $request->email)->first();

        if(!$user) {
            return response()->json([
                'error' => 'User not found'
            ], 404);
        }

        if(!Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'Invalid password'
            ], 401);
        }

        $credentials = request(['email', 'password']);

        $token = auth('api')->attempt($credentials);

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token, $user);
    }

    public function me() {
        return response()->json(new LoggedUserResource(auth()->user()));
    }

    protected function respondWithToken($token, $user)
    {
        return response()->json([
            'user' => new LoggedUserResource($user),
            'access_token' => $token
        ]);
    }
}
