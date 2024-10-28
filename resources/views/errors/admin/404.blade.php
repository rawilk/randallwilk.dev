@php
    $message ??= null;
@endphp

<x-layout.error
    :title="__('messages.errors.404.title')"
    code="404"
    icon="heroicon-o-face-frown"
    :message="$message ?: __($exception->getMessage()) ?: __('messages.errors.404.message', ['app_name' => config('app.name')])"
    :code-explanation="__('messages.errors.404.code_explanation')"
    :visitor-instructions="__('messages.errors.404.visitor_instructions', ['email' => config('randallwilk.support_email')])"
    :owner-instructions="__('messages.errors.404.owner_instructions')"
>
    <x-errors.back-button
        url="/admin"
    />
</x-layout.error>
