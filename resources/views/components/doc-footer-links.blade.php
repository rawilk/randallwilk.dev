<div class="flex flex-col-reverse md:flex-row justify-between items-center">
    <div class="flex flex-col items-center md:items-start">
        @if ($previous)
            <span class="font-bold text-blue-gray-500 text-sm tracking-wider uppercase pb-1">← Previous Topic</span>
            <h4 class="font-bold underline m-0">
                <a href="{{ $previous->url }}" class="link-black link-underline">{{ $previous->title }}</a>
            </h4>
        @endif
    </div>

    <div class="mb-8 md:mb-0 flex flex-col items-center md:items-end">
        @if ($next)
            <span class="font-bold text-blue-gray-500 text-sm tracking-wider uppercase pb-1">Next Topic →</span>
            <h4 class="font-bold underline m-0">
                <a href="{{ $next->url }}" class="link-black link-underline">{{ $next->title }}</a>
            </h4>
        @endif
    </div>
</div>
