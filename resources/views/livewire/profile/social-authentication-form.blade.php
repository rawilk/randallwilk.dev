<div>
    <x-card>
        <x-slot:header>
            <h2>{{ __('users.profile.social_auth.title') }}</h2>
            <p class="text-sm text-gray-500">{{ __('users.profile.social_auth.subtitle') }}</p>
        </x-slot:header>

        <div>
            <div id="error-wrapper"></div>

            {{-- github --}}
            <div>
                <div class="flex items-start space-x-4">
                    <div>
                        <x-svg-github-docs class="w-8 h-8 fill-slate-500" />
                    </div>

                    <div>
                        <p class="text-sm uppercase font-medium tracking-wider text-slate-600">{{ __('users.profile.social_auth.github_title') }}</p>

                        @if ($this->user->github_id)
                            <p class="text-sm text-slate-500">
                                {{ __('users.profile.social_auth.connected_account', ['username' => $this->user->github_username]) }}
                            </p>

                            <div class="mt-3">
                                <x-button color="red" wire:click="disconnectFromGitHub">
                                    {{ __('users.profile.social_auth.disconnect_button', ['provider' => 'GitHub']) }}
                                </x-button>
                            </div>
                        @else
                            <p class="text-sm text-slate-500">{{ __('users.profile.social_auth.github_desc') }}</p>

                            <div class="mt-3">
                                <x-button :href="route('login.github')" color="green">
                                    {{ __('users.profile.social_auth.connect_button', ['provider' => 'GitHub']) }}
                                </x-button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-card>

    @push('js')
        @include('layouts.partials.socialite-error-notification', ['errorWrapper' => 'error-wrapper'])
    @endpush
</div>
