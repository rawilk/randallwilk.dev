<?php

declare(strict_types=1);

namespace App\Http\Livewire\Auth\Passwords;

use App\Actions\Concerns\PasswordValidationRules;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;

final class Reset extends Component
{
    use PasswordValidationRules;

    public string $token;
    public string $email = '';
    public string $password = '';
    public bool $needsEmail = true;

    // $token will be set automatically by Livewire's magic.
    public function mount(Request $request, string $token): void
    {
        if ($request->has('email')) {
            $this->email = $request->get('email');
            $this->needsEmail = false;
        }
    }

    public function resetPassword()
    {
        $this->resetErrorBag();

        $this->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'password' => $this->passwordRules(needsConfirm: false),
        ]);

        $response = $this->broker()->reset(
            [
                'token' => $this->token,
                'email' => $this->email,
                'password' => $this->password,
            ],
            function ($user, $password) {
                $user->password = $password;

                $user->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));

                $this->guard()->login($user);
            }
        );

        if ($response === Password::PASSWORD_RESET) {
            Session::flash(__($response));

            return redirect(route('profile.show'));
        }

        $this->addError($this->needsEmail ? 'email' : 'password', __($response));
    }

    private function broker(): PasswordBroker
    {
        return Password::broker();
    }

    private function guard(): StatefulGuard
    {
        return Auth::guard();
    }

    public function render(): View
    {
        return view('livewire.auth.passwords.reset')
            ->layout('layouts.auth.base', ['title' => __('Reset password')]);
    }
}
