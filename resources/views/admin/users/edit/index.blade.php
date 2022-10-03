@extends('admin.users.edit.layout', [
    'title' => pageTitle($user->name->full, __('base::users.index.title'))
])

@section('slot')
    <livewire:users.user-details-form :user="$user" />

    <livewire:users.update-password-form :user="$user" />

    @canany(['impersonate', 'delete'], $user)
        <livewire:users.user-actions :user="$user" />
    @endcanany
@endsection
