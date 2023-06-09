<?php

namespace App\Services;

use App\Mail\DeleteUserMail;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Http\JsonResponse;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserService
{
    use CanResetPassword;

    public function store(array $registerData): User
    {
        return User::create($registerData);
    }

    public function login(array $loginData):  ? \Illuminate\Contracts\Auth\Authenticatable
    {
        Auth::attempt($loginData);

        return Auth::user();
    }

    public function sendResetLink(array $data)
    {
        $status = Password::sendResetLink(
            $data
        );

        return $status === Password::RESET_LINK_SENT
        ? response()->json(['status' => ($status)])
        : response()->json(['email' => ($status)]);
    }

    public function resetPassword(array $data) : JsonResponse
    {
        $status = Password::reset(
            $data,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
        ? response()->json(['status' => ($status)])
        : response()->json(['token' => [($status)]]);
    }

    public function updateUser($data, $user)
    {
        return $user->update($data);
    }

    public function showList(): array
    {
        $collection = User::all();
        return $collection->pluck('email')->toArray();
    }

    public function destroyUser($user):  ? SentMessage
    {
        $user->status = User::INACTIVE;
        $user->save();

        return Mail::to(['email' => $user->email])->send(new DeleteUserMail($user));
    }
}
