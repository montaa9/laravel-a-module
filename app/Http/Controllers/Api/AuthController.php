<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * User registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $user = User::create($validated);

        return response()->json([
            'user' => new UserResource($user),
            'access_token' => $user->createToken('app')->plainTextToken,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * User login
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($validated)) {
            return response()->json([
                'user' => new UserResource(auth()->user()),
                'access_token' => auth()->user()->createToken('app')->plainTextToken,
            ]);
        }

        return response()->json([
            'message' => 'Unauthorized.'
        ], 403);
    }

    /**
     * @return UserResource
     *
     * return authenticated user
     */
    public function user()
    {
        return new UserResource(auth()->user());
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     *
     * User logout
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'Logged out'
        ]);
    }
}
