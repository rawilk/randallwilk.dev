<div>
    <button x-on:click="open = ! open"
            x-bind:class="{ 'menu-item--active': hasActiveChild || open }"
            class="menu-item group w-full"
            aria-haspopup="true"
    >
        @isset ($icon)
            <x-dynamic-component :component="$icon" class="menu-item__icon" />
        @endisset

        {{ $label }}

        <svg class="ml-auto h-5 w-5 transform group-hover:text-cool-gray-400 group-focus:text-cool-gray-400 transition-colors"
             x-bind:class="{ 'text-cool-gray-400 rotate-90': open, 'text-cool-gray-300': ! open }"
             viewBox="0 0 20 20"
        >
            <path d="M6 6L14 10L6 14V6Z" fill="currentColor" />
        </svg>
    </button>
</div>
