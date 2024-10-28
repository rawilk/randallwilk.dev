<x-layout.error
    :title="__('messages.errors.403.title')"
    code="403"
    icon="heroicon-m-no-symbol"
    :message="$exception->getMessage() ?: __('messages.errors.403.message', ['app_name' => config('app.name')])"
    :code-explanation="__('messages.errors.403.code_explanation')"
    :visitor-instructions="__('messages.errors.403.visitor_instructions', ['email' => config('randallwilk.support_email')])"
    :owner-instructions="__('messages.errors.403.owner_instructions')"
>
    <x-errors.back-button
        url="/admin"
    />
</x-layout.error>
