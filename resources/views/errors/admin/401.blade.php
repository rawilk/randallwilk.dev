<x-layout.error
    :title="__('messages.errors.401.title')"
    code="401"
    icon="heroicon-m-no-symbol"
    :message="$exception->getMessage() ?: __('messages.errors.401.message', ['app_name' => config('app.name')])"
    :code-explanation="__('messages.errors.401.code_explanation')"
    :visitor-instructions="__('messages.errors.401.visitor_instructions', ['email' => config('randallwilk.support_email')])"
    :owner-instructions="__('messages.errors.401.owner_instructions')"
>
    <x-errors.back-button
        url="/admin"
    />
</x-layout.error>
