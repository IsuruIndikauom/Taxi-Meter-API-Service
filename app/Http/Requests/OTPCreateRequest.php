<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OTPCreateRequest extends FormRequest {
    /**
    * Determine if the user is authorized to make this request.
    */

    public function authorize(): bool {
        return true;
        // made all request unauthorized
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
    */

    public function rules(): array {
        return [
            'country_code' => 'required',
            'mobile_number' => 'required'
        ];

    }
}
