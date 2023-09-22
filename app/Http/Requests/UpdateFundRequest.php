<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFundRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'start_year' => ['required', 'date_format:Y'],
            'aliases' => ['nullable', 'array'],
            'aliases.*' => ['present:aliases', 'string', 'max:255'],
            'companies' => ['nullable', 'array'],
            'companies.*' => ['present:companies', 'numeric', 'exists:companies,id'],
            'fund_manager_id' => ['required', 'numeric', 'exists:fund_managers,id'],
        ];
    }
}
