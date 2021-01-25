<x-admin-app title="{{ formatPageTitle($repository->name, __('repositories.page_title')) }}">
    <x-slot name="pageTitle">
        <x-admin-page-title>{{ $repository->name }}</x-admin-page-title>
    </x-slot>

    <livewire:admin.repositories.details :repository="$repository" />
</x-admin-app>
