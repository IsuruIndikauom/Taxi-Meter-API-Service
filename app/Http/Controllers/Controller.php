<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;

class Controller extends BaseController {
    use AuthorizesRequests, ValidatesRequests;

    public const SUCCESS = 200;
    public const NOT_FOUND = 404;
    public const ERROR = 500;
    public const NO_CONTENT = 204;
    public const BAD_REQUEST = 400;

    public function success( $message = null, $data = null ) {
        return Response::json( [ 'message' => $message, 'data' => $data, 'code' => self::SUCCESS ], self::SUCCESS );
    }

    public function error( $message = null, $data = null ) {
        return Response::json( [ 'message' => $message, 'data' => $data, 'code' => self::ERROR ], self::ERROR );
    }

    public function notFound( $message = null, $data = null ) {
        return Response::json( [ 'message' => $message, 'data' => $data, 'code' => self::NOT_FOUND ], self::NOT_FOUND );
    }

    public function noContent( $message = null, $data = null ) {
        return Response::json( [ 'message' => $message, 'data' => $data, 'code' => self::NO_CONTENT ], self::NO_CONTENT );
    }

    public function badRequest( $message = null, $data = null ) {
        return Response::json( [ 'message' => $message, 'data' => $data, 'code' => self::BAD_REQUEST ], self::BAD_REQUEST );
    }

}
