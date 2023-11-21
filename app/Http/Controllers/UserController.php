<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Models\User;

class UserController extends Controller {
    public function store( UserCreateRequest $request, User $user ) {
        $user = $user->createUser( $request );
        return $this->success( 'User created', $user );
    }
}
