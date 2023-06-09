<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserListRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Update the given user.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->userService->updateUser($request->validated(), $user);
        return response()->json(['message' => 'Updated!']);
    }

    public function showList(UserListRequest $request)
    {
        return $this->userService->showList();
    }

    public function show(UserRequest $request, User $user)
    {
        return response()->json(new UserResource($user));
    }

    public function destroy(DeleteUserRequest $request, User $user)
    {
        $this->userService->destroyUser($user);
        return response()->json(['message' => 'Account deleted']);
    }
}
