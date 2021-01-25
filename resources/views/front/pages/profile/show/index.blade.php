@extends('front.pages.profile.layout', [
    'title' => __('users.profile.account_info.page_title'),
])

@section('slot')
    <livewire:profile.update-profile-information />

    <livewire:profile.delete-user />
@endsection
