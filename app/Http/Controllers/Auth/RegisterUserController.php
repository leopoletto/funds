<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;

class RegisterUserController extends Controller
{
    public function __invoke(RegisterUserRequest $request): User
    {
        return User::create($request->validated());
    }
}
