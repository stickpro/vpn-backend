<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\AuthRequest;
use App\Http\Resources\ErrorResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginUser(AuthRequest $request, AuthService $service)
    {
        $user = $service->auth($request->input('phone'));

        return JsonResource::make(['token' => $user->createToken('inner')->plainTextToken]);
    }

    public function createUser(AuthRequest $request, AuthService $service)
    {
        $credentials = [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            return JsonResource::make([
                    'user' => Auth::user(),
                    'token' => Auth::user()->createToken('inner')->plainTextToken,
            ]);
        } else {
            return  ErrorResource::make(message: 'The provided credentials do not match our records.');
        }
    }

    public function userInfo(Authenticatable $auth) {
        return JsonResource::make($auth);
    }
}