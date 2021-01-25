<x-slot name="nav">
    <x-inner-nav-item href="{{ route('open-source.packages') }}"
                      icon="heroicon-o-tag"
                      :active="request()->route()->named('open-source.packages')"
    >
        {{ __('front.repositories.packages_tab') }}
    </x-inner-nav-item>

    <x-inner-nav-item href="{{ route('open-source.projects') }}"
                      icon="heroicon-o-terminal"
                      :active="request()->route()->named('open-source.projects')"
    >
        {{ __('front.repositories.projects_tab') }}
    </x-inner-nav-item>
</x-slot>
