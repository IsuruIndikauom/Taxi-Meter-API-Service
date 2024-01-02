<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EndTripV2Request extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'total_tarrif' => 'required',
            'distance_tarrif' => 'required',
            'ride_distance' => 'required',
            'waiting_tarrif' => 'required',
            'total_waiting_time' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'total_tarrif.required' => 'The total tariff is required.',
            'distance_tarrif.required' => 'The distance tariff is required.',
            'ride_distance.required' => 'The ride distance is required. Send Meters here',
            'waiting_tarrif.required' => 'The waiting tariff is required.',
            'total_waiting_time.required' => 'The total waiting time is required. Send seconds here',
        ];
    }
}
