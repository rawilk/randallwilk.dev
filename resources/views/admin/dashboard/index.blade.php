<x-admin-app title="{{ __('dashboard.title') }}" :show-title="false">
    {{-- main 3 column grid --}}
    <div class="grid grid-cols-1 gap-4 items-start lg:grid-cols-3 lg:gap-8">

        {{-- left column --}}
        <div class="grid grid-cols-1 gap-4 lg:col-span-3">

            {{-- welcome panel --}}
            <section aria-labelledby="profile-overview-title">
                <div class="rounded-lg bg-white overflow-hidden shadow">
                    <h2 class="sr-only" id="profile-overview-title">{{ __('Profile Overview') }}</h2>

                    <div class="bg-white p-6">
                        <div class="sm:flex sm:items-center sm:justify-between">
                            <div class="sm:flex sm:space-x-5">
                                <div class="flex-shrink-0">
                                    <img class="mx-auto h-20 w-20 rounded-full" src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name->full }} avatar">
                                </div>

                                <div class="mt-4 text-center sm:mt-0 sm:pt-1 sm:text-left">
                                    <p class="text-sm font-medium text-gray-600">{{ __('Welcome back,') }}</p>
                                    <p class="text-xl font-bold text-gray-900 sm:text-2xl">{{ auth()->user()->name->full }}</p>
                                    <p class="text-sm font-medium text-gray-600">
                                        {{ auth()->user()->roles->map(fn ($role) => $role->name)->implode(', ') }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5 flex justify-center sm:mt-0" x-data>
                                <x-button
                                    :href="route('profile.show')"
                                    color="slate"
                                >
                                    {{ __('View profile') }}
                                </x-button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- widgets --}}
            <div class="mt-2 grid grid-cols-1 gap-5 sm:grid-cols-2">
                {{-- user count --}}
                <x-admin.dashboard.user-count />

                {{-- visible repository count --}}
                <x-admin.dashboard.visible-repo-count />
            </div>


        </div>

    </div>

</x-admin-app>
