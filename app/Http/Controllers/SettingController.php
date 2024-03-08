<?php

namespace App\Http\Controllers;

use App\Models\Tariff;

class SettingController extends Controller
{
    public function get()
    {
        return $this->success('Settings', $this->settingResponse());
    }

    public function settingResponse()
    {
        return [
            'tariff' => Tariff::where('status', 1)->first(),
        ];
    }
}
