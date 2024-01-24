<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTariffRequest;
use App\Http\Requests\UpdateTariffRequest;
use App\Models\Tariff;

class TariffController extends Controller
{

    public function index()
    {
        return $this->success('All tariffs', Tariff::all());
    }

    public function store(CreateTariffRequest $request, Tariff $tariff)
    {
        return $this->success('Tariff created', $tariff->createTariff($request));
    }

    public function update(UpdateTariffRequest $request, Tariff $tariff)
    {
        return $this->success('Tariff updated', $tariff->updateTariff($request));
    }

    public function show(Tariff $tariff)
    {
        return $this->success('Tariff details', $tariff);
    }

    public function destroy(Tariff $tariff)
    {
        $tariff->delete();
        return $this->success('Tariff deleted');
    }

    public function getActive()
    {
        return $this->success('Tariff details', Tariff::where('status', 1)->first());
    }
}
