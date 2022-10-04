@props([
    'title' => '',
    'url' => '',
    'target' => '_self',
])

<li class="w-full">
    <article {{ $attributes->class('group relative flex flex-col items-start') }}>
        <h2 class="text-base font-semibold tracking-tight text-slate-800 static">
            <div class="absolute -inset-y-6 -inset-x-4 z-0 scale-95 bg-slate-50 opacity-0 transition group-hover:scale-100 group-hover:opacity-100 sm:-inset-x-6 sm:rounded-2xl">
            </div>
            <a href="{{ $url }}" class="static" @if ($target === '_blank') target="_blank" rel="noopener noreferrer" @endif>
                <span class="absolute -inset-y-6 -inset-x-4 sm:-inset-x-6 sm:rounded-2xl z-20"></span>
                <span class="relative z-10">{{ $title }}</span>
            </a>
        </h2>
        {{ $slot }}
    </article>
</li>
