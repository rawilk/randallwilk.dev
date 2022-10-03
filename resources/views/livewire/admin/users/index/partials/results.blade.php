<x-table>
    <x-slot:head>
        <x-tr>
            <x-th class="table-check">
                <x-checkbox wire:model="selectPage" />
            </x-th>
            <x-th class="table-actions" />
            <x-th sortable multi-column wire:click="sortBy('id')" :direction="$sorts['id'] ?? null" :hidden="$this->isHidden('id')">{{ __('ID') }}</x-th>
            <x-th sortable multi-column wire:click="sortBy('name')" :direction="$sorts['name'] ?? null" :hidden="$this->isHidden('name')" class="w-full">{{ __('Name') }}</x-th>
            <x-th sortable multi-column wire:click="sortBy('email')" :direction="$sorts['email'] ?? null" :hidden="$this->isHidden('email')">{{ __('Email') }}</x-th>
            <x-th :hidden="$this->isHidden('roles')">{{ __('Roles') }}</x-th>
            <x-th sortable multi-column wire:click="sortBy('timezone')" :direction="$sorts['timezone'] ?? null" :hidden="$this->isHidden('timezone')">{{ __('Timezone') }}</x-th>
            <x-th sortable multi-column wire:click="sortBy('created_at')" :direction="$sorts['created_at'] ?? null" :hidden="$this->isHidden('created_at')">{{ __('Created') }}</x-th>
            <x-th sortable multi-column wire:click="sortBy('updated_at')" :direction="$sorts['updated_at'] ?? null" :hidden="$this->isHidden('updated_at')">{{ __('Last Updated') }}</x-th>
        </x-tr>
    </x-slot:head>

    <x-laravel-base::table.selected-message
        :select-page="$selectPage"
        :select-all="$selectAll"
        :count="$users->count()"
        :total="$users->total()"
        :colspan="$this->visibleColumns"
        item-name="users"
    />

    @forelse ($users as $user)
        <x-tr row-index="{{ $user->id }}"
              wire:key="row-{{ $user->id }}"
              :selected="$this->isSelected($user->id)"
              wire-loads
        >
            <x-td class="pr-0">
                <x-checkbox wire:model="selected" value="{{ $user->id }}" />
            </x-td>

            <x-td class="table-actions">
                <x-action-menu>
                    @can('edit', $user)
                        <x-dropdown-item href="{{ $user->edit_url }}">
                            <x-heroicon-s-pencil />
                            <span>{{ __('base::messages.edit_button') }}</span>
                        </x-dropdown-item>
                    @endcan

                    @can('impersonate', $user)
                        <x-laravel-base::auth.impersonate-button :user-id="$user->getAuthIdentifier()">
                            <x-dropdown-item>
                                <x-heroicon-s-arrows-right-left />
                                <span>{{ __('base::users.impersonate.button') }}</span>
                            </x-dropdown-item>
                        </x-laravel-base::auth.impersonate-button>
                    @endcan

                    @can('delete', $user)
                        <x-dropdown-divider />
                        <x-dropdown-item wire:click="confirmDelete({{ $user->id }})">
                            <x-css-trash />
                            <span>{{ __('base::messages.delete_button') }}</span>
                        </x-dropdown-item>
                    @endcan
                </x-action-menu>
            </x-td>

            <x-td :hidden="$this->isHidden('id')">{{ $user->id }}</x-td>
            <x-td :hidden="$this->isHidden('name')">
                <div class="flex items-center space-x-2">
                    <img class="h-8 w-8 rounded-full" src="{{ $user->avatar_url }}" alt="{{ $user->name->full }}">

                    <div>
                        @can('edit', $user)
                            <x-link href="{{ $user->edit_url }}">
                                {!! $this->highlight(e($user->name)) !!}
                            </x-link>
                        @else
                            <span>{!! $this->highlight(e($user->name)) !!}</span>
                        @endif

                        <div>
                            @can('impersonate', $user)
                                <x-laravel-base::auth.impersonate-button :user-id="$user->getAuthIdentifier()">
                                    <x-laravel-base::button.link
                                        class="text-xs"
                                        dark
                                    >
                                        {{ __('base::users.impersonate.button') }}
                                    </x-laravel-base::button.link>
                                </x-laravel-base::auth.impersonate-button>
                            @endcan
                        </div>
                    </div>
                </div>
            </x-td>

            <x-td :hidden="$this->isHidden('email')">
                <x-link href="mailto:{{ $user->email }}" dark hide-external-indicator>
                    {!! $this->highlight(e($user->email)) !!}
                </x-link>
            </x-td>

            <x-td :hidden="$this->isHidden('roles')">
                <div class="flex items-center flex-wrap space-y-2">
                    @foreach ($user->roles as $role)
                        <x-badge variant="blue">{{ $role->name }}</x-badge>
                    @endforeach
                </div>
            </x-td>
            <x-td :hidden="$this->isHidden('timezone')">{{ $user->timezone }}</x-td>
            <x-td :hidden="$this->isHidden('created_at')">{{ $user->created_at_for_humans }}</x-td>
            <x-td :hidden="$this->isHidden('updated_at')">{{ $user->updated_at_for_humans }}</x-td>
        </x-tr>
    @empty
        <x-laravel-base::table.no-results colspan="{{ $this->visibleColumns }}">
            {{ __('No users found...') }}
        </x-laravel-base::table.no-results>
    @endforelse
</x-table>

<div class="content-container">
    {{ $users->links() }}
</div>
