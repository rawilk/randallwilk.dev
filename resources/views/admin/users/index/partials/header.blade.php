<x-slot:page-title>
    <x-layout.page-title>
        {{ __('base::users.index.title') }}

        @can('create', \App\Models\User\User::class)
            <x-slot:actions>
                <x-button
                    color="blue"
                    :href="route('admin.users.create')"
                    left-icon="css-math-plus"
                    x-data="{}"
                >
                    <span class="capitalize">{{ __('base::messages.add_button', ['item' => __('users.singular')]) }}</span>
                </x-button>
            </x-slot:actions>
        @endcan
    </x-layout.page-title>
</x-slot:page-title>
