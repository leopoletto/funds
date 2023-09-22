<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchFundsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable'],
            'start_year' => ['nullable', 'integer'],
            'fund_manager_id' => ['nullable', 'exists:fund_managers,id'],
        ];
    }
}
