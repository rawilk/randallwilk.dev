<x-slot:page-title>
    <x-layout.page-title>
        {{ __('base::users.index.title') }}

        @can('create', \App\Models\User\User::class)
            <x-slot:actions>
                <x-button variant="blue" href="{!! route('admin.users.create') !!}">
                    <x-css-math-plus />
                    <span class="capitalize">{{ __('base::messages.add_button', ['item' => __('users.singular')]) }}</span>
                </x-button>
            </x-slot:actions>
        @endcan
    </x-layout.page-title>
</x-slot:page-title>
