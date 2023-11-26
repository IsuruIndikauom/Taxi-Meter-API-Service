<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTarrifRequest extends FormRequest {
    /**
    * Get the validation rules that apply to the request.
    *
    * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
    */

    public function rules(): array {
        return [
            'fix_rate' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'rate_per_km' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'rate_per_minute' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'status' => 'required|boolean',
        ];
    }
}
