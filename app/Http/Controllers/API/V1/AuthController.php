<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! Auth::attempt($credentials, (bool) ($request->boolean('remember')))) {
            return ApiResponse::error('Invalid credentials.', null, 422);
        }

        $request->session()->regenerate();

        return ApiResponse::success('Logged in.', [
            'user' => $request->user(),
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return ApiResponse::success('', [
            'user' => $request->user(),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return ApiResponse::success('Logged out.');
    }
}

