<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeedFundRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1'],
            'duplicate' => ['nullable', 'numeric', 'min:1'],
        ];
    }
}
