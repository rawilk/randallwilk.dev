@props([
    'icon',
    'theme',
])

@php
    $label = __("filament-panels::layout.actions.theme_switcher.{$theme}.label");
@endphp

<button
    aria-label="{{ $label }}"
    type="button"
    x-on:click="theme = @js($theme)"
    x-tooltip="{
        content: @js($label),
        theme: $store.theme,
    }"
    x-show="shouldShow(@js($theme))"
    x-cloak
    class="text-gray-600 dark:text-amber-400"
>
    <x-filament::icon
        :alias="'panels::theme-switcher.' . $theme . '-button'"
        :icon="$icon"
        class="h-6 w-6"
    />
</button>
