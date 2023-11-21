<?php

namespace App\Services;
use App\Contracts\CreateOTP;

class DummyOTPService implements CreateOTP {
    public  function create() {
        return '0000';
    }
}