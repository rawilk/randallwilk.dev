@include('layouts.partials.session-alert', ['canDismissAlert' => true])

<x-feedback.socialite-alert />

<div>
    <div class="grid xl:grid-cols-2 gap-4">
        {{ $this->passkeyLoginAction }}

        {{ $this->githubLoginAction }}
    </div>

    <div class="relative mt-2 -mb-4 flex py-5 items-center text-sm">
        <div class="flex-grow border-t border-gray-200 dark:border-gray-600"></div>
        <span class="flex-shrink mx-4 text-gray-400 dark:text-gray-200">Or use email</span>
        <div class="flex-grow border-t border-gray-200 dark:border-gray-600"></div>
    </div>
</div>
