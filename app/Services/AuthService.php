<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Contracts\Session\Session;

class AuthService
{
    public function __construct(
        private StatefulGuard $auth,
        private Hasher $hash,
        private Session $session,
    ) {}

    public function login(array $credentials): bool
    {
        if ($this->auth->attempt($credentials)) {
            $this->session->regenerate();
            return true;
        }

        return false;
    }

    public function register(array $data): User
    {
        $isFirstUser = User::count() === 0;

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $this->hash->make($data['password']),
            'is_admin' => $isFirstUser,
        ]);

        $this->auth->login($user);

        return $user;
    }

    public function logout(): void
    {
        $this->auth->logout();
        $this->session->invalidate();
        $this->session->regenerateToken();
    }
}