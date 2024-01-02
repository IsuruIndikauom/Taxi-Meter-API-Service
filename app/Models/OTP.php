<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model
{
    protected $table = 'otps';
    use HasFactory;
    protected $guarded = [];

    public function createOTP($data, $otp)
    {
        $data->merge(['otp' => $otp]);
        $data->merge(['mobile_number' => ltrim($data->mobile_number, '0')]);
        $otp = $this->updateOrCreate(
            ['mobile_number' => $data->mobile_number], // Conditions for matching existing record
            $data->all() // Data to be updated or inserted
        );
        return [];
    }
}
