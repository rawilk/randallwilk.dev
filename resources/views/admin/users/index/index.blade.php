<x-admin-app title="{{ __('base::users.index.title') }}">
    @include('admin.users.index.partials.header')

    <livewire:users.index />
</x-admin-app>
