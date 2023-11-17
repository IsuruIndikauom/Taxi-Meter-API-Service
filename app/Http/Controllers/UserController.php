<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Models\User;

class UserController extends Controller
{
    public function store(UserCreateRequest $request)
    {
        if (in_array(config('roles.' . $request->role_id), ['Developer', 'Admin'])) {
            $request->validate([
                'email' => ' required',
                'password' => 'required',
            ]);
        }
        $user = User::create($request->all());
        return response()->json($user);
    }
}
