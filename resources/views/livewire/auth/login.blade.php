<div>
    <x-auth.authentication-form title="{{ __('auth.login.title') }}">
        <div>
            <p class="text-sm font-medium text-slate-700">
                {{ __('auth.login.social_login_title') }}
            </p>

            <div class="mt-1 grid grid-cols-1">
                <div>
                    <x-button
                        variant="white"
                        block
                        onclick="event.preventDefault(); showGitHubAuthWindow();"
                        href="{!! route('login.github') !!}"
                    >
                        <span class="sr-only">{{ __('auth.socialite.login_via_github') }}</span>
                        <x-svg-github class="h-6 w-6 fill-current text-slate-600" />
                    </x-button>
                </div>
            </div>

            <div class="mt-6 mb-6 relative">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-gray-300"></div>
                </div>

                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">
                        {{ __('auth.login.app_login_title') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="space-y-6" id="form-wrapper">
            @include('layouts.partials.session-alert')

            <x-form wire:submit.prevent="login">
                {{-- email --}}
                <x-form-group label="{{ __('Email address') }}" name="email">
                    <x-email
                        wire:model.defer="email"
                        name="email"
                        autofocus
                        autocomplete="email"
                        required
                    />
                </x-form-group>

                {{-- password --}}
                <x-form-group label="{{ __('Password') }}" name="password">
                    <x-password
                        wire:model.defer="password"
                        name="password"
                        required
                    />
                </x-form-group>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <x-checkbox wire:model.defer="remember" name="remember">
                            {{ __('Remember me') }}
                        </x-checkbox>
                    </div>

                    @if (Route::has('password.request'))
                        <div class="text-sm leading-5">
                            <x-link href="{!! route('password.request') !!}" hide-external-indicator>
                                {{ __('auth.login.forgot_password_link') }}
                            </x-link>
                        </div>
                    @endif
                </div>

                <div class="mt-6">
                    <x-button variant="blue" type="submit" block wire:target="login">
                        {{ __('auth.login.login_button') }}
                    </x-button>
                </div>
            </x-form>
        </div>

        @env('local')
            <div class="mt-10 text-center rounded-lg bg-gray-100 p-3 text-sm">
                <div class="text-lg text-slate-600 font-semibold mb-3">Development Logins</div>
                <x-login-link
                    email="randall@randallwilk.dev"
                    label="Login as super admin"
                    redirect-url="{{ route('admin.dashboard') }}"
                />
            </div>
        @endenv
    </x-auth.authentication-form>

    @push('js')
        @include('layouts.partials.socialite-error-notification', ['errorWrapper' => 'form-wrapper'])

        <script>
            function showGitHubAuthWindow() {
                const authWindow = window.open(
                    '{!! route('login.github') !!}',
                    null,
                    'location=0,status=0,width=800,height=400',
                );

                const authCheckInterval = window.setInterval(() => {
                    if (authWindow.closed) {
                        window.clearInterval(authCheckInterval);
                        window.location.replace('{{ session('next', defaultLoginRedirect()) }}');
                    }
                }, 500);
            }
        </script>
    @endpush
</div>
