<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Auth;
use Illuminate\Http\Request;
use Throwable;

class AuthController extends Controller
{
    public function register(UserRegRequest $request, AuthService $service)
    {
        try {
            $user = $service->register($request->validated());
            Auth::login($user);
            $user->load('organization');
            $token = auth()->user()->createToken('api_token')->plainTextToken;
            return response()->json([
                'message' => 'User Register successfully',
                'user' => new UserResource($user),
                'token' => $token,
            ], 201);
        } catch (Throwable $th) {
            return response()->json(['message' => 'something went wrong ' . $th->getMessage()], 500);
        }
    }
    public function login(UserLoginRequest $request, AuthService $service)
    {

        try {
            $user = $service->attemptAuth($request->validated());
            Auth::login($user);
            $user->load('organization');
            $token = auth()->user()->createToken('api_token')->plainTextToken;
            return response()->json([
                'message' => 'User Logged in successfully',
                'user' => new UserResource($user),
                'token' => $token,
            ], 201);

        } catch (Throwable $th) {
            return response()->json(['message' => 'something went wrong ' . $th->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }
    public function me()
    {
        $user = auth()->user();
        $user->load('organization');

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        return new UserResource($user);
    }

}
