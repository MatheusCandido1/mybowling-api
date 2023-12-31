<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Requests\User\UserUpdatePasswordRequest;

use App\Http\Resources\Auth\LoggedUserResource;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function firstAaccess() {
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

    public function password(Request $request) {
        try {

            $user = auth()->user();

            if(!Hash::check($request->currentPassword, $user->password)) {
                return response()->json([
                    'message' => 'Current password is incorrect.',
                ], 400);
            }

            if($request->newPassword != $request->newPasswordConfirmation) {
                return response()->json([
                    'message' => 'New password and confirmation do not match.',
                ], 400);
            }

            $user->password = Hash::make($request->newPassword);
            $user->save();

            return response()->json([
                'message' => 'Password updated'
            ], 202);

        } catch(\Exception $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UserUpdateRequest $request) {
        try {

            $user = auth()->user();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            return response()->json([
                'data' => new LoggedUserResource($user),
                'message' => 'User updated'
            ], 202);

        } catch(\Exception $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }

    public function avatar(Request $request) {
        try {
            $user = auth()->user();

            $path = $request->file('file')->store('avatars');

            // $path = str_replace('avatars/', '', $path);

            $user->avatar = $path;
            $user->save();

            return response()->json([
                'data' => new LoggedUserResource($user),
                'message' => 'Avatar updated'
            ], 202);

        } catch(\Exception $e) {
            return response()->json([
                'error_message' => $e->getMessage(),
            ], 500);
        }
    }



}
