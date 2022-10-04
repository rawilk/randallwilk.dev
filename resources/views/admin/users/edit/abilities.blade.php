@extends('admin.users.edit.layout', [
    'title' => pageTitle(__('users.abilities.page_title'), $user->name->full, __('base::users.index.title'))
])

@section('slot')
    <livewire:users.user-abilities-form :user="$user" />
@endsection
