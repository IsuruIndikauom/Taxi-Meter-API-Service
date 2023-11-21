<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OTP extends Model {
    protected $table = 'otps';
    use HasFactory;
    protected $guarded = [];

    public function response() {
        return [ 'otp'=> $this->otp, 'mobile_number'=>$this->mobile_number ] ;
    }
}

