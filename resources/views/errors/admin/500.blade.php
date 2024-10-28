<x-layout.error
    :title="__('messages.errors.500.title')"
    code="500"
    :message="$exception->getMessage() ?: __('messages.errors.500.message', ['app_name' => config('app.name')])"
    :code-explanation="__('messages.errors.500.code_explanation')"
    :visitor-instructions="__('messages.errors.500.visitor_instructions', ['email' => config('randallwilk.support_email')])"
    :owner-instructions="__('messages.errors.500.owner_instructions')"
>
    <x-errors.back-button
        url="/admin"
    />
</x-layout.error>
