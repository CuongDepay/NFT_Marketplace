<?php

namespace App\Http\Services\Users;

use App\Jobs\SendRegistrationEmail;
use App\Models\PasswordReset;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    public function login($email, $password)
    {
        try {
            $data = [
                "email" => $email,
                "password" => $password,
            ];
            Auth::attempt($data);
            return  Auth::user();
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function userFindByEmail($email)
    {
        return User::where("email", $email)->first();
    }

    public function checkAccountLogged($email)
    {
        $sessions =  Session::with("user")->get();
        $lifeTime = Carbon::now()->getTimestamp() - (config("session.lifetime"));
        foreach ($sessions as $session) {
            if ($session->user->email === $email) {
                if ($session->last_activity >= $lifeTime) {
                    return true;
                } else {
                    $session->delete();
                }
            }
        }
        return false;
    }

    public function logout($userId)
    {
        $session = Session::all()->where("user_id", "=", $userId)->first();

        if ($session) {
            Auth::logout();
            $session->delete();
            return true;
        }

        return false;
    }

    public function register($userData)
    {
        try {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'gender' => 2,
                $this->createVerificationToken()
            ]);

            $this->sendEmailVerify($user);

            return $user;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function sendEmailVerify($user)
    {
        $user->update($this->createVerificationToken());

        dispatch(new SendRegistrationEmail($user));
    }

    public function verityEmail($token)
    {
        $user = User::where('verification_token', $token)
                    ->where('verification_token_expires_at', '>', now())
                    ->whereNull("email_verified_at")->first();
        if (!$user) {
            return null;
        }

        $user->email_verified_at = now()->toDateTimeString();
        $user->verification_token = null;
        $user->verification_token_expires_at = null;
        $user->save();
        return $user;
    }

    private function createVerificationToken()
    {
        return [
            'verification_token' =>  Str::random(64),
            "verification_token_expires_at" => Carbon::now()->addMinutes(5),
        ];
    }

    public function createTokenPasswordReset($email)
    {
        $user = User::where('email', $email)->firstOrFail();

        $passwordReset = PasswordReset::updateOrCreate([
            'email' => $user->email,
        ], [
            'token' => Str::random(64),
            'created_at' => now(),
        ]);

        return ["user" => $user,"token" => $passwordReset->token];
    }

    public function getPasswordResetToken($token)
    {
        return PasswordReset::where("token", $token)->first();
    }

    public function updatePasswordAndRemoveToken($passwordResetToken, $password)
    {
        $user = User::where('email', $passwordResetToken->email)->first();
        if (!$user) {
            return false;
        }
        $user->update([
            "password" => Hash::make($password),
        ]);
        $passwordResetToken->delete();
        return true;
    }
}
