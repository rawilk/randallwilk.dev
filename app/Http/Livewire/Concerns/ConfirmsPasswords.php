<?php

namespace App\Http\Livewire\Concerns;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

/** @mixin \Livewire\Component */
trait ConfirmsPasswords
{
    /*
     * Indicates if the user's password is being confirmed.
     */
    public bool $confirmingPassword = false;

    /**
     * The ID of the operation being confirmed.
     *
     * @var string|null
     */
    public $confirmableId = null;

    /*
     * The user's password.
     */
    public string $confirmablePassword = '';

    /**
     * Start confirming the user's password.
     *
     * @param string $confirmableId
     */
    public function startConfirmingPassword(string $confirmableId): void
    {
        $this->resetErrorBag();

        if ($this->passwordIsConfirmed()) {
            $this->dispatchBrowserEvent('password-confirmed', [
                'id' => $confirmableId,
            ]);

            return;
        }

        $this->confirmingPassword = true;
        $this->confirmableId = $confirmableId;
        $this->confirmablePassword = '';

        $this->dispatchBrowserEvent('confirming-password');
    }

    /**
     * Stop confirming the user's password.
     */
    public function stopConfirmingPassword(): void
    {
        $this->confirmingPassword = false;
        $this->confirmableId = null;
        $this->confirmablePassword = '';
    }

    /**
     * Confirm the user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'confirmablePassword' => ['required', 'string', 'password'],
        ], [
            'required' => __('Your password is required.'),
            'password' => __('This password does not match our records.'),
        ]);

        Session::put('auth.password_confirmed_at', time());

        $this->dispatchBrowserEvent('password-confirmed', [
            'id' => $this->confirmableId,
        ]);

        $this->stopConfirmingPassword();
    }

    /**
     * Ensure that the user's password has been recently confirmed.
     *
     * @param int|null $maximumSecondsSinceConfirmation
     * @return void
     */
    protected function ensurePasswordIsConfirmed($maximumSecondsSinceConfirmation = null)
    {
        $maximumSecondsSinceConfirmation = $maximumSecondsSinceConfirmation ?: Config::get('auth.password_timeout', 900);

        return $this->passwordIsConfirmed($maximumSecondsSinceConfirmation)
            ? null
            : abort(Response::HTTP_FORBIDDEN);
    }

    /**
     * Determine if the user's password has been recently confirmed.
     *
     * @param int|null $maximumSecondsSinceConfirmation
     * @return bool
     */
    protected function passwordIsConfirmed($maximumSecondsSinceConfirmation = null): bool
    {
        $maximumSecondsSinceConfirmation = $maximumSecondsSinceConfirmation ?: Config::get('auth.password_timeout', 900);

        return (time() - Session::get('auth.password_confirmed_at', 0)) < $maximumSecondsSinceConfirmation;
    }
}
