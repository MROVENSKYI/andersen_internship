<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Services\UserService;


class AuthController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function register(RegisterRequest $request)
    {
        $user = $this->userService->store($request->validated());

        $token = $user->createToken('API Token')->accessToken;

        return response()->json(['token' => $token], Response::HTTP_CREATED);
    }

}