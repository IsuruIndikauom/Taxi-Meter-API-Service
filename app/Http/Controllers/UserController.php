<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
Use Auth;

class UserController extends Controller {

    public function index() {
        return $this->success( 'All users', User::all() );
    }

    public function store( UserCreateRequest $request, User $user ) {
        $user = $user->createUser( $request );
        return $this->success( 'User created', $user );
    }

    public function update( UserUpdateRequest $request, User $user ) {
        $user = $user->updateUser( $request, $user );
        return $this->success( 'User updated', $user );
    }

    public function show( User $user ) {
        return $this->success( 'User details', $user );
    }

    public function destroy( User $user ) {
        $user->delete();
        return $this->success( 'User deleted' );
    }

    public function logout() {
        $user = Auth::User();
        if ( $user && $user->token() ) {
            $user->token()->revoke();
            return $this->success( 'Successfully logged out' );
        }

        return $this->badRequest( 'No token found' );
    }
}
