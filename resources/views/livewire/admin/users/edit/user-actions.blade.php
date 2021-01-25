<div>
    <x-card flush :rounded-on-mobile="false">
        <x-slot name="header">
            <h2 class="card__title">{{ __('labels.actions') }}</h2>
        </x-slot>

        <ul class="divide-y divide-y-blue-gray-200">
            @can('impersonate', $user)
                <li>
                    <div class="px-4 py-4 flex items-center sm:px-6">
                        <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <div class="text-sm leading-5 font-medium text-blue-gray-600 truncate">{{ __('users.actions.impersonate_title') }}</div>

                                <div class="mt-2 flex">
                                    <span class="text-sm leading-5 text-blue-gray-500">
                                        {{ __('users.actions.impersonate_desc', ['name' => $user->name->first]) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex-shrink-0 sm:mt-0">
                            <x-button wire:click="impersonate" variant="outline-danger" class="sm:button--lg">
                                {{ __('users.actions.impersonate_button') }}
                            </x-button>
                        </div>
                    </div>
                </li>
            @endcan

            @can('delete', $user)
                <li>
                    <div class="px-4 py-4 flex items-center sm:px-6">
                        <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <div class="text-sm leading-5 font-medium text-blue-gray-600 truncate">{{ __('users.actions.delete_title') }}</div>

                                <div class="mt-2 flex">
                                    <span class="text-sm leading-5 text-blue-gray-500">
                                        {{ __('users.actions.delete_desc', ['name' => $user->name->first_possessive]) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex-shrink-0 sm:mt-0">
                            <x-button wire:click="confirmDelete" variant="outline-danger" class="sm:button--lg">
                                {{ __('labels.delete_button') }}
                            </x-button>
                        </div>
                    </div>
                </li>

                @include('livewire.admin.users.edit.partials.confirm-delete')
            @endcan
        </ul>
    </x-card>
</div>
