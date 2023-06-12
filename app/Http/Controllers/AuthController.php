<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetLinkRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Response;


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
    public function login(LoginRequest $request)
    {
        $user = $this->userService->login($request->validated());

        $token = $user->createToken('API Token')->accessToken;

        return response()->json(['token' => $token], Response::HTTP_OK);
    }
    public function sendResetLink(ResetLinkRequest $request)
    {
        return $this->userService->sendResetLink($request->only('email'));
    }
    public function resetPassword(ResetPasswordRequest $request)
    {
        return $this->userService->resetPassword($request->validated());
    }

}