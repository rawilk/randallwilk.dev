<div>
    <div class="flex items-center px-4">
        {{-- user info --}}
        <div class="flex-shrink-0">
            <img class="h-10 w-10 rounded-full"
                 src="{{ Auth::user()->avatar_url }}"
                 alt="{{ Auth::user()->name->full }}"
            >
        </div>
        <div class="ml-3">
            <div class="text-base font-medium text-blue-gray-800">{{ Auth::user()->name->full }}</div>
            <div class="text-sm font-medium text-blue-gray-500">{{ Auth::user()->email }}</div>
        </div>
    </div>
</div>
