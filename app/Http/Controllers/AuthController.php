<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController
{
    public function __construct(
        private AuthService $authService,
    ) {}

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        if ($this->authService->login($request->validated())) {
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Неверный email или пароль',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());

        $message = $user->is_admin
            ? 'Добро пожаловать! Вы первый пользователь и автоматически стали администратором.'
            : 'Добро пожаловать!';

        return redirect('/')->with('success', $message);
    }

    public function logout(Request $request)
    {
        $this->authService->logout();

        return redirect('/');
    }
}