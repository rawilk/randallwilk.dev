@props([
    'next' => false,
    'previous' => false,
])

@php
    /** @var \App\Docs\DocumentationPage|null $next */
    /** @var \App\Docs\DocumentationPage|null $previous */
@endphp

<dl class="mt-12 flex border-t border-slate-200 pt-6 dark:border-slate-800">
    {{-- previous page --}}
    @if ($previous)
        <div>
            <dt class="font-display text-sm font-medium text-slate-900 dark:text-white">
                {{ __('front.docs.show.previous_link') }}
            </dt>
            <dd class="mt-1">
                <a href="{{ $previous->url }}"
                   class="text-base font-semibold text-slate-500 hover:text-slate-600 dark:text-slate-400 dark:hover:text-slate-300"
                >
                    <span aria-hidden="true">&larr;</span>
                    <span>{{ $previous->title }}</span>
                </a>
            </dd>
        </div>
    @endif

    {{-- next page --}}
    @if ($next)
        <div class="ml-auto text-right">
            <dt class="font-display text-sm font-medium text-slate-900 dark:text-white">
                {{ __('front.docs.show.next_link') }}
            </dt>
            <dd class="mt-1">
                <a href="{{ $next->url }}"
                   class="text-base font-semibold text-slate-500 hover:text-slate-600 dark:text-slate-400 dark:hover:text-slate-300"
                >
                    <span>{{ $next->title }}</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </dd>
        </div>
    @endif
</dl>
