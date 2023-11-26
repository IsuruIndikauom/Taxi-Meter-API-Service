<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTariffRequest;
use App\Models\Tariff;

class TariffController extends Controller {
    public function store( CreateTariffRequest $request, Tariff $tariff ) {

        return $this->success( 'Tariff Created', $tariff->createTariff ( $request ) );

    }
}
