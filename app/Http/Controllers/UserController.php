<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Models\User;

class UserController extends Controller
{
    public function store(UserCreateRequest $request)
    {
        $user = User::create($request->all());
        return response()->json($user);
    }
}
