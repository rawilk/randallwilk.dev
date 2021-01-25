@php
    // This is kind of useful (also kind of hacky) for pages like "App Settings" that share a common url
    // with pages like "Email Settings", but shouldn't be denoted as "active" when the other pages
    // are visited.
    $requiresExactUrlMatch = $requiresExactUrlMatch ?? false;

    $linkActive = $requiresExactUrlMatch
        ? $active && request()->url() === $url
        : $active;
@endphp

<a href="{{ $url }}"
   x-bind:class="{ 'hidden': ! open }" {{-- The `open` variable comes from the ExpandableMenu macro used registered for the menu package --}}
   class="{{ $linkActive ? 'active ' . config('site.tenant.main_menu.item_active_class') : config('site.tenant.main_menu.item_inactive_class') }} {{ config('site.tenant.main_menu.submenu_item_class') }}"
   @if ($active && request()->url() === $url) aria-current="page" @endif
>
    {{ $label }}
</a>
