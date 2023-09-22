<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReconcileFundRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'funds' => ['required', 'array', 'exists:funds,id']
        ];
    }
}
