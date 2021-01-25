<a href="{{ $url }}"
   class="{{ config($active ? 'site.main_menu.item_active_class' : 'site.main_menu.item_inactive_class') }} {{ config('site.main_menu.item_base_class') }}"
   @if ($active && request()->url() === $url) aria-current="page" @endif
>
    <x-dynamic-component
        :component="$icon"
        class="{{ config('site.main_menu.icon_base_class') }} {{ config($active ? 'site.main_menu.icon_active_class' : 'site.main_menu.icon_inactive_class') }}"
    />

    {{ $label }}
</a>
