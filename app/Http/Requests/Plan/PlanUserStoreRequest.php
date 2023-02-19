<?php

namespace App\Http\Requests\Plan;

use Illuminate\Foundation\Http\FormRequest;

class PlanUserStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'plan_id' => ['required', 'integer']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}