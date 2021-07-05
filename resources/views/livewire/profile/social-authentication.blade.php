<div>
    <x-card>
        <x-slot name="header">
            <h2 class="card__title">{{ __('users.profile.authentication.social_auth_title') }}</h2>
            <p class="text-sm text-cool-gray-500">{{ __('users.profile.authentication.social_auth_sub_title') }}</p>
        </x-slot>

        <div>
            {{-- github --}}
            <div>
                <div class="flex items-start space-x-4">
                    <div>
                        <div class="w-8 h-8 text-blue-gray-500">
                            {{ renderSvg('github') }}
                        </div>
                    </div>

                    <div>
                        <p class="text-sm uppercase font-medium tracking-wider text-blue-gray-600">{{ __('users.profile.authentication.social_github_title') }}</p>

                        @if ($this->user->github_id)
                            <p class="text-sm text-blue-gray-500">
                                {{ __('users.profile.authentication.connected_account', ['username' => $this->user->github_username]) }}
                            </p>

                            <div class="mt-3">
                                <x-button variant="red" wire:click="disconnectFromGithub" wire:target="disconnectFromGithub">
                                    {{ __('users.profile.authentication.disconnect_from_provider_button', ['provider' => 'GitHub']) }}
                                </x-button>
                            </div>
                        @else
                            <p class="text-sm text-blue-gray-500">
                                {{ __('users.profile.authentication.social_github_connect_desc') }}
                            </p>

                            <div class="mt-3">
                                <x-button href="{!! route('login.github') !!}" variant="success">
                                    {{ __('users.profile.authentication.connect_to_provider_button', ['provider' => 'GitHub']) }}
                                </x-button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-card>
</div>
