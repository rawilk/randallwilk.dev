<div>
    <x-authentication-form :title="__('Sign in to your account')">
        <div>
            <p class="text-sm font-medium text-blue-gray-700">
                {{ __('Sign in with') }}
            </p>

            <div class="mt-1 grid grid-cols-1">
                <div>
                    <a href="{{ route('login.github') }}"
                       onclick="event.preventDefault(); showGithubAuthWindow();"
                       class="w-full inline-flex justify-center py-2 px-4 border border-blue-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-blue-gray-500 hover:bg-blue-gray-50"
                    >
                        <span class="sr-only">{{ __('Sign in with GitHub') }}</span>
                        <span class="h-6 w-6">{{ renderSvg('github') }}</span>
                    </a>
                </div>
            </div>

            <div class="mt-6 mb-6 relative">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">
                        {{ __('Or continue with') }}
                    </span>
                </div>
            </div>
        </div>

        <x-session-alert type="error" class="alert alert--danger alert--border mb-6" />

        <x-form wire:submit.prevent="login">
            <div class="space-y-6">

                <x-form-group :label="__('Email address')" name="email">
                    <x-email wire:model.defer="email"
                             name="email"
                             autofocus
                             required
                    />
                </x-form-group>

                <x-form-group :label="__('Password')" name="password">
                    <x-password wire:model.defer="password"
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

                    <div class="text-sm leading-5">
                        <a href="{{ route('password.request') }}" class="app-link">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>
                </div>

                <div>
                    <x-button type="submit" block variant="primary" wire:target="login">
                        {{ __('Sign in') }}
                    </x-button>
                </div>

            </div>
        </x-form>
    </x-authentication-form>

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (window.opener && window.opener !== window) {
                    // This is an auth popup. We can close this window and
                    // the parent window will take care of the user.
                    window.close();
                }
            });

            function showGithubAuthWindow() {
                const authWindow = window.open(
                    '{{ route('login.github') }}',
                    null,
                    'location=0,status=0,width=800,height=400',
                );

                const authCheckInterval = window.setInterval(() => {
                    if (authWindow.closed) {
                        window.clearInterval(authCheckInterval);
                        window.location.replace('{{ defaultLoginRedirect() }}');
                    }
                }, 500);
            }
        </script>
    @endpush
</div>
