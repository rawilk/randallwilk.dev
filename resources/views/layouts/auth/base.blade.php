@include('layouts.partials.page-scripts-and-styles')

<x-app :title="$title">
    <div class="min-h-screen bg-white flex">
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-15 lg:flex-none lg:px-20 lg:w-1/2 xl:w-1/3">
            {{ $slot }}
        </div>

        <div class="hidden lg:block relative w-0 flex-1">
            <img class="absolute inset-0 h-full w-full object-cover"
                 src="{{ config('site.auth_bg_image') }}"
                 alt="login cover image"
            >
        </div>
    </div>
</x-app>
