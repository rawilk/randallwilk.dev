<div>
    <x-slot name="pageTitle">
        <x-admin-page-title>{{ __('repositories.page_title') }}</x-admin-page-title>
    </x-slot>

    <div class="py-4 space-y-4">
        <div class="px-4 lg:px-0">
            {{-- topbar --}}
            <x-elements.index-topbar :show-columns="false">
                <x-dropdown trigger-text="{{ __('Actions') }}" with-background right>
                    <x-dropdown-item wire:click="syncRepos" wire:loading.attr="disabled" wire:target="syncRepos">
                        <x-heroicon-s-refresh wire:loading.class="animate-spin" wire:target="syncRepos" />

                        <span>{{ __('Sync Repositories') }}</span>
                    </x-dropdown-item>

                    <x-dropdown-item wire:click="syncDocs" wire:loading.attr="disabled" wire:target="syncDocs">
                        <x-heroicon-s-refresh wire:loading.class="animate-spin" wire:target="syncDocs" />

                        <span>{{ __('Sync Docs') }}</span>
                    </x-dropdown-item>
                </x-dropdown>
            </x-elements.index-topbar>

            {{-- advanced search --}}
            @include('livewire.admin.repositories.index.partials.advanced-search')
        </div>

        {{-- results --}}
        <ul class="pt-8 grid grid-cols-1 gap-6 xl:grid-cols-2">
            @forelse ($repositories as $repository)
                <li class="col-span-1" wire:key="repo-{{ $repository->id }}">
                    @include('livewire.admin.repositories.index.partials.repository', ['repository' => $repository])
                </li>
            @empty
                <li class="col-span-1">
                    <div class="flex items-center space-x-2">
                        <x-heroicon-o-information-circle class="h-5 w-5 text-red-400" />

                        <p class="text-lg text-blue-gray-600 font-medium">
                            {{ __('No repositories found...') }}
                        </p>
                    </div>
                </li>
            @endforelse
        </ul>

        <div class="px-4 lg:px-0">
            {{ $repositories->links() }}
        </div>
    </div>

    {{-- delete --}}
    @include('livewire.admin.repositories.index.partials.confirm-delete')

    {{-- form --}}
    @include('livewire.admin.repositories.index.partials.form')
</div>
