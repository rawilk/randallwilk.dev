@extends('admin.users.show.layout', [
    'title' => formatPageTitle($user->name->full, __('users.page_title')),
])

@section('slot')
    <livewire:admin.users.user-details-form :user="$user" />

    @if (auth()->user()->is_admin)
        <livewire:admin.users.account-info-form :user="$user" />
    @endif

    <livewire:admin.users.update-password-form :user="$user" />

    @canany(['impersonate', 'delete'], $user)
        <livewire:admin.users.user-actions :user="$user" />
    @endcanany
@endsection
