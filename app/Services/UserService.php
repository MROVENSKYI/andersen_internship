<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;




class UserService
{
    use CanResetPassword;
    public function store(array $registerData): User
    {
        return User::create($registerData);
    }

    public function login(array $loginData)
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
            ? response()->json(['status' => __($status)])
            : response()->json(['email' => __($status)]);
    }

    public function resetPassword(array $data)
    {
        $status = Password::reset(
            $data,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['status' => __($status)])
            : response()->json(['token' => [__($status)]]);
    }

    public function updateUser($data, $user)
    {
        return $user->update($data);
    }
}