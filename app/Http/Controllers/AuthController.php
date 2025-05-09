<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    /**
     * Registration Api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegistrationRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);
        $token =  $user->createToken('access_token', ['*'], now()->addDay())->plainTextToken;

        return $this->sendSuccessJson([
            'token' => $token,
            'user' => $user,
        ], 'User register successfully.', 201);
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if(!Auth::attempt($request->only('email', 'password'))) {
            return $this->sendErrorJson(
                'Unauthorized.',
                ['error' => 'Invalid credentials'],
                401
            );
        }
        $user = Auth::user();
        $token = $user->createToken('access_token', ['*'], now()->addDay())->plainTextToken;

        return $this->sendSuccessJson([
            'token' => $token,
            'name' => $user->name,
        ], 'Login successfully.');
    }

    /**
     * Get profile API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request): JsonResponse
    {
        return $this->sendSuccessJson([
            'user' => $request->user(),
        ], 'Get profile.');
    }

    /**
     * Logout API
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->sendSuccessJson(null, 'Logout successful.');
    }
}