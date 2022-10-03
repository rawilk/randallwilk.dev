<div>
    <x-card flush>
        <x-slot:header>
            <h2>{{ __('Actions') }}</h2>
        </x-slot:header>

        <ul class="divide-y divide-y-slate-200">
            @can('impersonate', $user)
                <li>
                    <div class="px-4 py-4 flex items-center sm:px-6">
                        <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <div class="text-sm leading-5 font-medium text-slate-600 truncate">{{ __('Impersonate User') }}</div>

                                <div class="mt-2 flex">
                                     <span class="text-sm leading-5 text-slate-500">
                                         {{ __('See what :name sees by impersonating their user account.', ['name' => $user->first_name]) }}
                                     </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex-shrink-0 sm:mt-0">
                            <x-laravel-base::auth.impersonate-button :user-id="$user->getAuthIdentifier()">
                                <x-button variant="outline-red" class="sm:button--lg">
                                    {{ __('base::users.impersonate.button') }}
                                </x-button>
                            </x-laravel-base::auth.impersonate-button>
                        </div>
                    </div>
                </li>
            @endcan

            @can('delete', $user)
                <li>
                    <div class="px-4 py-4 flex items-center sm:px-6">
                        <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <div class="text-sm leading-5 font-medium text-gray-600 truncate">{{ __('Delete User') }}</div>

                                <div class="mt-2 flex">
                                 <span class="text-sm leading-5 text-gray-500">
                                     {{ __('Permanently delete :first_name account and any data associated with it.', ['first_name' => $user->name->first_possessive]) }}
                                 </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex-shrink-0 sm:mt-0">
                            <x-button wire:click="confirmDelete" variant="outline-red" class="sm:button--lg">
                                {{ __('Delete') }}
                            </x-button>
                        </div>
                    </div>
                </li>

                @include('livewire.admin.users.edit.partials.confirm-delete')
            @endcan
        </ul>
    </x-card>
</div>
