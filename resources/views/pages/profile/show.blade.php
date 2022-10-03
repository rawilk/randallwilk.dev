@extends('pages.profile.layout', [
    'title' => __('users.profile.account.page_title'),
])

@section('slot')
    <livewire:profile.update-profile-information-form />

    @if (\Rawilk\LaravelBase\Features::hasAccountDeletionFeatures())
        <livewire:profile.delete-user-form />
    @endif
@endsection
