<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'phone' => ['required'],
            'auth_code' => ['sometimes', 'min:4', 'max:4'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}