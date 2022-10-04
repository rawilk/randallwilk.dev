@props([
    'type' => 'regular',
])

<x-dynamic-component
    :component="'svg-logo.' . $type"
    {{ $attributes->class(array_filter(['logo', $type === 'dual' ? 'logo--dual' : null])) }}
/>
