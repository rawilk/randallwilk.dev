@extends('pages.profile.layout', [
    'title' => __('users.profile.authentication.title'),
])

@section('slot')
    <livewire:profile.update-password-form />

    <livewire:profile.social-authentication-form />

    @if (\Rawilk\LaravelBase\Features::canManageTwoFactorAuthentication() || \Rawilk\LaravelBase\Features::canManageWebauthnAuthentication())
        <x-card>
            <x-slot:header>
                <h2>{{ __('base::users.profile.two_factor_title') }}</h2>
                <p class="text-sm text-slate-500">
                    {{ __('base::users.profile.two_factor_sub_title') }}
                </p>
            </x-slot:header>

            @unless (isImpersonating())
                <div class="divide-y divide-slate-300 space-y-4">
                    {{-- webauthn --}}
                    @if (\Rawilk\LaravelBase\Features::canManageWebauthnAuthentication())
                        @push('js')
                            @webauthnScripts
                        @endpush

                        <livewire:profile.webauthn-security-keys-form :user="auth()->user()" />
                        <livewire:profile.webauthn-internal-keys-form :user="auth()->user()" />
                    @endif

                    {{-- totp 2fa --}}
                    @if (\Rawilk\LaravelBase\Features::canManageTwoFactorAuthentication())
                        <livewire:profile.two-factor-authentication-form :user="auth()->user()" />
                    @endif

                    {{-- recovery codes --}}
                    <livewire:profile.2fa-recovery-codes :user="auth()->user()" />
                </div>
            @endunless

            @if (isImpersonating())
                <p class="texts-sm text-slate-500">{{ __('MFA form not available while impersonating.') }}</p>
            @endif
        </x-card>
    @endif
@endsection
