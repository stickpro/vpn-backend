<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:64'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'max:64'],
            'terms' => ['accepted']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}