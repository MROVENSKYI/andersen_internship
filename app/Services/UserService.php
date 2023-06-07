<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;


class UserService
{
    public function store(array $registerData): User
    {
        return User::create($registerData);
    }

    public function login(array $loginData)
    {
        Auth::attempt($loginData);

        return Auth::user();
    }

}