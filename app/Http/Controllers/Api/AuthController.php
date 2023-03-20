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
        if (empty($request->input('authCode'))) {
            $service->sendCode($request->input('phone'));
            return JsonResource::make(['message' => 'Code Send']);
        }
        $user = $service->auth($request->input('phone'), $request->input('authCode'));

        return JsonResource::make(['token' => $user->createToken('inner')->plainTextToken]);
    }

    public function userInfo(Authenticatable $auth) {
        return JsonResource::make($auth);
    }
}
