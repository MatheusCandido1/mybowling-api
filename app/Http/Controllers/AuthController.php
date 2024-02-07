<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\PasswordReset;

use App\Http\Resources\Auth\LoggedUserResource;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

use App\Mail\forgotPasswordMail;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'forgotPassword', 'validatePassworCode', 'resetPassword']]);
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

    public function resetPassword(Request $request) {
        try {
            $email = $request->email;
            $password = $request->password;
            $token = $request->token;

            $passwordReset = PasswordReset::where('email', $email)->where('token', $token)->first();

            if(!$passwordReset) {
                return response()->json([
                    'error' => 'Invalid token',
                ], 400);
            }

            $user = User::where('email', $email)->first();

            $user->password = bcrypt($password);
            $user->save();

            PasswordReset::where('email', $email)->delete();

            return response()->json([
                'message' => 'Password updated, you can now login with your new password'
            ], 202);

        } catch(\Exception $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);

        }
    }

    public function validatePassworCode(Request $request) {
        try {
            $email = $request->email;
            $token = $request->token;

            $passwordReset = PasswordReset::where('email', $email)->where('token', $token)->where('expires_at', '>', now())->first();

            if(!$passwordReset) {
                return response()->json([
                    'error' => 'Invalid token',
                ], 400);
            }

            return response()->json([
                'message' => 'Token is valid'
            ], 202);

        } catch(\Exception $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);

        }
    }

    public function forgotPassword(Request $request) {
        try {
            $email = $request->email;

            $user = User::where('email', $email)->first();

            if(!$user) {
                return response()->json([
                    'error' => 'We could not find a user with that email.',
                ], 404);
            }

            $token = sprintf("%06d", mt_rand(1, 999999));

            // Check if there is a token for this email
            $passwordReset = PasswordReset::where('email', $email)->first();

            if($passwordReset) {
                $passwordReset->delete();
            }

            $newPasswordReset = new PasswordReset();
            $newPasswordReset->email = $user->email;
            $newPasswordReset->token = $token;
            $newPasswordReset->expires_at = now()->addHours(24);
            $newPasswordReset->save();

            // Mail::to('matheus12bh@gmail.com')->send(new forgotPasswordMail());


            return response()->json([
                'message' => 'Validation code sent to your email.'
            ], 202);


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
