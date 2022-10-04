<x-info-list>
    {{-- name --}}
    <x-info-list-item label="{{ __('repos.labels.name') }}">
        <x-link dark href="{{ $repository->url }}" target="_blank">
            <span>{{ $repository->full_name }}</span>
        </x-link>
    </x-info-list-item>

    {{-- description --}}
    <x-info-list-item label="{{ __('repos.labels.description') }}">
        {{ $repository->description }}
    </x-info-list-item>

    {{-- type --}}
    <x-info-list-item label="{{ __('repos.labels.type') }}">
        {{ $repository->type?->label() ?? __('repos.labels.type_not_set') }}
    </x-info-list-item>

    {{-- language --}}
    <x-info-list-item label="{{ __('repos.labels.language') }}">{{ $repository->language->value }}</x-info-list-item>

    {{-- visibility --}}
    <x-info-list-item label="{{ __('repos.labels.visibility') }}">
        <x-badge :variant="$repository->visible ? 'green' : 'red'">
            {{ $repository->visible ? __('repos.labels.visible') : __('repos.labels.hidden') }}
        </x-badge>
    </x-info-list-item>

    {{-- docs url --}}
    <x-info-list-item label="{{ __('repos.labels.docs_url') }}">
        @if ($repository->documentation_url)
            <x-link href="{{ $repository->documentation_url }}" target="_blank" dark>
                <span>{{ $repository->documentation_url }}</span>
            </x-link>
        @endif
    </x-info-list-item>

    {{-- blopost url --}}
    <x-info-list-item label="{{ __('repos.labels.blogpost_url') }}">
        @if ($repository->blogpost_url)
            <x-link href="{{ $repository->blogpost_url }}" target="_blank" dark>
                <span>{{ $repository->blogpost_url }}</span>
            </x-link>
        @endif
    </x-info-list-item>

    {{-- new --}}
    <x-info-list-item label="{{ __('repos.labels.marked_new') }}">
        <span class="sr-only">{{ $repository->new ? __('Yes') : __('No') }}</span>
        <x-dynamic-component
            :component="$repository->new ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle'"
            @class([
                'h-5 w-5',
                'text-red-500' => ! $repository->new,
                'text-green-500' => $repository->new,
            ])
        />
    </x-info-list-item>

    {{-- featured --}}
    <x-info-list-item label="{{ __('repos.labels.marked_featured') }}">
        <span class="sr-only">{{ $repository->highlighted ? __('Yes') : __('No') }}</span>
        <x-dynamic-component
            :component="$repository->highlighted ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle'"
            @class([
                'h-5 w-5',
                'text-red-500' => ! $repository->highlighted,
                'text-green-500' => $repository->highlighted,
            ])
        />
    </x-info-list-item>

    {{-- created at --}}
    <x-info-list-item label="{{ __('repos.labels.repo_created_at') }}">
        <span>{{ $repository->repository_created_at_for_humans }}</span>

        <div class="mt-1 italic text-xs">{{ __('repos.labels.repo_created_at_help') }}</div>
    </x-info-list-item>

    {{-- stars --}}
    <x-info-list-item label="{{ __('repos.labels.stars') }}">
        {{ $repository->stars_for_humans }}
    </x-info-list-item>

    {{-- downloads --}}
    @if ($repository->isPackage())
        <x-info-list-item label="{{ __('repos.labels.downloads') }}">
            {{ $repository->downloads_for_humans }}
        </x-info-list-item>
    @endif

    {{-- topics --}}
    <x-info-list-item label="{{ __('repos.labels.topics') }}">
        <div class="flex flex-wrap items-center space-x-2">
            @foreach ($repository->topics as $topic)
                <x-badge variant="blue">{{ $topic }}</x-badge>
            @endforeach
        </div>
    </x-info-list-item>
</x-info-list>
