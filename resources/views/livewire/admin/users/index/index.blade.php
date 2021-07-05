<div>
    <x-slot name="pageTitle">
        <x-admin-page-title>
            {{ __('users.page_title') }}

            @can('create', \App\Models\User::class)
                <x-slot name="actions">
                    <x-button href="{{ route('admin.users.create') }}" variant="blue">
                        <x-css-math-plus />

                        <span>{{ __('labels.forms.add_button', ['item' => __('users.singular')]) }}</span>
                    </x-button>
                </x-slot>
            @endcan
        </x-admin-page-title>
    </x-slot>

    <div class="py-4 space-y-4">
        <div class="px-4 lg:px-0">
            {{-- top bar --}}
            @include('livewire.admin.users.index.partials.topbar')

            {{-- advanced search --}}
            @include('livewire.admin.users.index.partials.advanced-search')
        </div>

        {{-- results --}}
        <x-table>
            <x-slot name="head">
                <x-tr>
                    <x-th class="no-padding pl-6 w-8">
                        <x-checkbox wire:model="selectPage" />
                    </x-th>
                    <x-th sortable multi-column wire:click="sortBy('id')" :direction="$sorts['id'] ?? null" :hidden="$this->isHidden('id')">{{ __('models.labels.id') }}</x-th>
                    <x-th sortable multi-column wire:click="sortBy('name')" :direction="$sorts['name'] ?? null" :hidden="$this->isHidden('name')">{{ __('users.labels.name') }}</x-th>
                    <x-th sortable multi-column wire:click="sortBy('email')" :direction="$sorts['email'] ?? null" :hidden="$this->isHidden('email')">{{ __('users.labels.email') }}</x-th>
                    <x-th sortable multi-column wire:click="sortBy('timezone')" :direction="$sorts['timezone'] ?? null" :hidden="$this->isHidden('timezone')">{{ __('users.labels.timezone') }}</x-th>
                    <x-th sortable multi-column wire:click="sortBy('is_admin')" :direction="$sorts['is_admin'] ?? null" :hidden="$this->isHidden('is_admin')">{{ __('users.labels.is_admin') }}</x-th>
                    <x-th sortable multi-column wire:click="created_at" :direction="$sorts['created_at'] ?? null" :hidden="$this->isHidden('created_at')">{{ __('models.labels.created_at') }}</x-th>
                    <x-th sortable multi-column wire:click="sortBy('updated_at')" :direction="$sorts['updated_at'] ?? null" :hidden="$this->isHidden('updated_at')">{{ __('models.labels.updated_at') }}</x-th>
                    <x-th />
                </x-tr>
            </x-slot>

            <x-table.selected-message
                :select-page="$selectPage"
                :select-all="$selectAll"
                :count="$users->count()"
                :total="$users->total()"
                :colspan="$this->visibleColumnCount"
                item-name="users"
            />

            @forelse ($users as $user)
                <x-tr aria-rowindex="{{ $user->id }}"
                      wire:key="row-{{ $user->id }}"
                      wire:loading.class.delay="opacity-50"
                      :selected="$this->isSelected($user->id)"
                >
                    <x-td class="pr-0">
                        <x-checkbox wire:model="selected" value="{{ $user->id }}" />
                    </x-td>
                    <x-td :hidden="$this->isHidden('id')">{{ $user->id }}</x-td>
                    <x-td :hidden="$this->isHidden('name')">
                        <div class="flex items-center space-x-2">
                            <img class="h-8 w-8 rounded-full" src="{{ $user->avatar_url }}" alt="{{ $user->name->full }}">

                            <div>
                                <a href="{{ $user->edit_url }}"
                                   class="app-link"
                                >
                                    {!! $this->highlight(e($user->name)) !!}
                                </a>

                                <div>
                                    @can('impersonate', $user)
                                        <x-button.link wire:click="impersonate({{ $user->id }})" dark class="text-xs">{{ __('users.actions.impersonate_button') }}</x-button.link>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </x-td>
                    <x-td :hidden="$this->isHidden('email')">
                        <a href="mailto:{{ $user->email }}"
                           class="app-link app-link--dark"
                        >
                            {!! $this->highlight(e($user->email)) !!}
                        </a>
                    </x-td>
                    <x-td :hidden="$this->isHidden('timezone')">{{ $user->timezone }}</x-td>
                    <x-td :hidden="$this->isHidden('is_admin')">
                        <x-badge :variant="$user->is_admin ? 'green' : 'red'">
                            {{ $user->is_admin ? __('labels.yes') : __('labels.no') }}
                        </x-badge>
                    </x-td>
                    <x-td :hidden="$this->isHidden('created_at')">{{ $user->created_at_for_humans }}</x-td>
                    <x-td :hidden="$this->isHidden('updated_at')">{{ $user->updated_at_for_humans }}</x-td>
                    <x-td class="text-right pr-2">
                        @if ($this->showDropdownFor($user))
                            <x-action-menu>
                                @can('edit', $user)
                                    <x-dropdown-item href="{{ $user->edit_url }}">
                                        <x-heroicon-s-pencil />

                                        <span>{{ __('labels.edit_button') }}</span>
                                    </x-dropdown-item>
                                @endif

                                @can('impersonate', $user)
                                    <x-dropdown-item wire:click="impersonate({{ $user->id }})">
                                        <x-heroicon-s-switch-horizontal />

                                        <span>{{ __('users.actions.impersonate_button') }}</span>
                                    </x-dropdown-item>
                                @endif

                                @can('delete', $user)
                                    <x-dropdown-item wire:click="confirmDelete({{ $user->id }})">
                                        <x-css-trash />

                                        <span>{{ __('labels.delete_button') }}</span>
                                    </x-dropdown-item>
                                @endcan
                            </x-action-menu>
                        @endif
                    </x-td>
                </x-tr>
            @empty
                <x-table.no-results :colspan="$this->visibleColumnCount">
                    {{ __('users.alerts.no_results') }}
                </x-table.no-results>
            @endforelse
        </x-table>

        <div class="px-4 lg:px-0">
            {{ $users->links() }}
        </div>
    </div>

    {{-- delete --}}
    @if (auth()->user()->is_admin)
        @include('livewire.admin.users.index.partials.confirm-delete')
    @endif
</div>
