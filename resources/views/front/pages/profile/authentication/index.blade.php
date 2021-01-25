@extends('front.pages.profile.layout', [
    'title' => __('users.profile.authentication.page_title'),
])

@section('slot')
    <livewire:profile.update-password-form />

    <livewire:profile.social-authentication />
@endsection
