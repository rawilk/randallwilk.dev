<x-admin-app title="{{ pageTitle($repository->name, __('repos.title')) }}">
    <x-slot:pageTitle>
        <x-layout.page-title>{{ $repository->name }}</x-layout.page-title>
    </x-slot:pageTitle>

    <livewire:admin.repositories.show :repository="$repository" />
</x-admin-app>
