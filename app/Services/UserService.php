<?php

namespace App\Services;

use App\Models\User;


class UserService
{

    public function store(array $data)
    {

        $user = User::create($data);

        return $user;
    }
}