@push('head')
    @include('layouts.partials.admin-styles')
@endpush

<x-app :title="$title ?? ''">
    <div class="min-h-screen bg-white flex">
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-15 lg:flex-none lg:px-20 lg:w-1/2 xl:w-1/3">
            {{ $slot }}
        </div>

        <div class="hidden lg:block relative w-0 flex-1">
            <img class="absolute inset-0 h-full w-full object-cover"
                 src="https://images.unsplash.com/photo-1483354483454-4cd359948304?ixid=MXwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=3000&q=80"
                 alt="login cover image"
            >
        </div>
    </div>
</x-app>
