<?php

declare(strict_types=1);

namespace App\Http\Livewire\Auth\Passwords;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

final class Email extends Component
{
    public string $email = '';
    public bool $emailSent = false;

    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $response = $this->broker()->sendResetLink(['email' => $this->email]);

        if ($response === Password::RESET_LINK_SENT) {
            $this->emailSent = true;

            Session::flash('success', __($response));

            return;
        }

        $this->addError('email', __($response));
    }

    public function broker(): \Illuminate\Contracts\Auth\PasswordBroker
    {
        return Password::broker();
    }

    public function render()
    {
        return view('livewire.auth.passwords.email')
            ->layout('layouts.auth.base', ['title' => __('Reset password')]);
    }
}
