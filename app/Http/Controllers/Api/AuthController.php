<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\ErrorResource;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function createUser(RegisterRequest $request)
    {
        $user = User::create([
                'name'     => $request->input('name'),
                'email'    => $request->input('email'),
                'password' => Hash::make($request->input('password')),
        ]);

        return JsonResource::make(['token' => $user->createToken('inner')->plainTextToken]);
    }

    public function loginUser(LoginRequest $request)
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