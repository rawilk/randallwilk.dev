<?php

declare(strict_types=1);

namespace App\Http\Livewire\Auth;

use App\Actions\Auth\AttemptToAuthenticate;
use App\Actions\Auth\EnsureLoginIsNotThrottled;
use App\Actions\Auth\PrepareAuthenticatedSession;
use Illuminate\Pipeline\Pipeline;
use Livewire\Component;

final class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = true;

    public function login()
    {
        $credentials = $this->validate([
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'string'],
        ]);

        request()->merge(array_merge($credentials, ['remember' => $this->remember]));

        return app(Pipeline::class)
            ->send(request())
            ->through([
                EnsureLoginIsNotThrottled::class,
                AttemptToAuthenticate::class,
                PrepareAuthenticatedSession::class,
            ])
            ->then(fn () => redirect()->intended(defaultLoginRedirect()));
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.auth.base', ['title' => __('Sign in to your account')]);
    }
}
