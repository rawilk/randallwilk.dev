<x-topbar
    :search-placeholder="__('repos.labels.search_placeholder')"
    :show-columns="false"
>
    {{-- actions --}}
    <div class="topbar-section">
        <x-dropdown trigger-text="{{ __('Actions') }}" right>
            {{-- sync repos --}}
            <x-dropdown-item wire:click="syncRepos" wire:loading.attr="disabled" wire:target="syncRepos">
                <x-heroicon-s-arrow-path wire:loading.class="animate-spin" wire:target="syncRepos" />
                <span>{{ __('repos.labels.sync_all_button') }}</span>
            </x-dropdown-item>

            {{-- sync docs --}}
            <x-dropdown-item wire:click="syncDocs" wire:loading.attr="disabled" wire:target="syncDocs">
                <x-heroicon-s-arrow-path wire:loading.class="animate-spin" wire:target="syncDocs" />
                <span>{{ __('repos.labels.sync_docs_button') }}</span>
            </x-dropdown-item>
        </x-dropdown>
    </div>

    @include('livewire.admin.repositories.index.partials.filters')
</x-topbar>

{{-- filter breadcrumbs --}}
@include('layouts.partials.filter-breadcrumbs')
