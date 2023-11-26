<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTarrifRequest;
use App\Models\Tarrif;

class TarrifController extends Controller {
    public function store( CreateTarrifRequest $request, Tarrif $tarrif ) {

        return $this->success( 'Tarrif Created', $tarrif->createTarrif ( $request ) );

    }
}
