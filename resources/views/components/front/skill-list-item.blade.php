@if ($url)
    <x-front.section-list-link-item url="{{ $url }}" title="{{ $skill }}" target="_blank">
        @if ($description)
            <p class="relative z-10 mt-2 text-sm text-slate-600">
                {{ $description }}
            </p>
        @endif

        <div aria-hidden="true" class="relative z-10 mt-4 flex items-center text-sm font-medium text-slate-400 group-hover:text-brand">
            <x-heroicon-m-link class="h-5 w-5 flex-none" />
            <span class="ml-2">{{ $url }}</span>
        </div>
    </x-front.section-list-link-item>
@else
    <x-front.section-list-item title="{{ $skill }}">
        @if ($description)
            <p>{{ $description }}</p>
        @endif
    </x-front.section-list-item>
@endif
