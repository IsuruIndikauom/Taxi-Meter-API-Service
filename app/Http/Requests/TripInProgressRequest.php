<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TripInProgressRequest extends FormRequest {
    /**
    * Get the validation rules that apply to the request.
    *
    * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
    */

    public function rules(): array {
        return [
            'start_latitude' => 'required|numeric',
            'start_longitude' => 'required|numeric',
        ];
    }
}
