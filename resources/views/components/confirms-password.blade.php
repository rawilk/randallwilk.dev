@props([
    'title' => __('users.actions.confirm_password_title'),
    'content' => __('users.actions.confirm_password_text'),
    'button' => __('users.actions.confirm_password_button'),
])

@php($confirmableId = md5($attributes->wire('then')))

<span
    {{ $attributes->wire('then') }}
    x-data
    x-ref="span"
    x-on:click="$wire.startConfirmingPassword('{{ $confirmableId }}')"
    x-on:password-confirmed.window="setTimeout(() => $event.detail.id === '{{ $confirmableId }}' && $refs.span.dispatchEvent(new CustomEvent('then', { bubbles: false })), 250);"
    class="inline"
>
    {{ $slot }}
</span>

@once
<x-dialog-modal wire:model="confirmingPassword" max-width="lg">
    <x-slot name="title">{{ $title }}</x-slot>

    <x-slot name="content">
        {{ $content }}

        <div class="mt-4">
            <x-password wire:model.defer="confirmablePassword"
                        wire:keydown.enter="confirmPassword"
                        name="confirmablePassword"
                        placeholder="{{ __('users.form.labels.password') }}"
                        autofocus
            />

            <x-form-error name="confirmablePassword" />
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-button wire:click="confirmPassword"
                  wire:target="confirmPassword"
                  variant="primary"
        >
            {{ $button }}
        </x-button>

        <x-button wire:click="stopConfirmingPassword"
                  wire:loading.attr="disabled"
                  variant="white"
        >
            {{ __('labels.confirm_modal_cancel') }}
        </x-button>
    </x-slot>
</x-dialog-modal>
@endonce
