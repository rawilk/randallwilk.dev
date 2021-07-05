@props(['on'])

<div x-data="{ shown: false, timeout: null }"
     x-init="$wire.on('{{ $on }}', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000);  })"
     x-show.transition.opacity.out.duration.1500ms="shown"
     style="display: none;"
     {{ $attributes->merge(['class' => 'text-sm text-green-600 font-medium leading-5', 'role' => 'alert']) }}
>
    <div class="flex-shrink-0">
        <span class="inline-flex">
            <span class="relative inline-flex items-center">
                <x-heroicon-s-check-circle class="mr-1 -ml-0.5 h-4 w-4" />

                <span>{{ $slot->isEmpty() ? __('alerts.saved') : $slot }}</span>
            </span>
        </span>
    </div>
</div>
