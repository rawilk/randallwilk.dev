<x-admin-app title="{{ __('repos.title') }}">
    <x-slot:pageTitle>
        <x-layout.page-title>{{ __('repos.title') }}</x-layout.page-title>
    </x-slot:pageTitle>

    <livewire:admin.repositories.index />
</x-admin-app>
