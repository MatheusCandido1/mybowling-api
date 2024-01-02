<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use App\Http\Resources\Auth\LoggedUserResource;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
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

    public function register(RegisterRequest $request) {
        try {

            $email = $request->email;

            $existingUser = User::where('email', $email)->first();

            if($existingUser) {
                return response()->json([
                    'error' => 'Email already registered',
                ], 400);
            }

            $user = new User();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);

            $user->save();

            $profile = $user->profile()->create([
                "first_access" => true,
                "user_id" => $user->id
            ]);

            $ball = $user->balls()->create([
                "name" => "House Ball",
                "weight" => "10",
                "color" => "#e5013b",
                "type" => "DEFAULT",
                "user_id" => $user->id
            ]);

            $credentials = request(['email', 'password']);

            $token = auth('api')->attempt($credentials);

            if (!$token) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return $this->respondWithToken($token, $user);
        } catch(\Exception $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }

    }

    public function me() {
        $user = auth()->user();

        return response()->json([
            'user' => new LoggedUserResource($user)
        ], 200);

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
