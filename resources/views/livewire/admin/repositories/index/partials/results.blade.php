<ul class="grid grid-cols-1 gap-6 xl:grid-cols-2">
    @forelse ($repositories as $repository)
        <li class="col-span-1" wire:key="repo{{ $repository->getKey() }}">
            @include('livewire.admin.repositories.index.partials.result', ['repository' => $repository])
        </li>
    @empty
        <li class="col-span-1" wire:key="repos-no-results">
            <div class="flex items-center space-x-2">
                <x-heroicon-o-information-circle class="h-5 w-5 text-red-500" />
                <p class="text-lg text-slate-600 font-medium">{{ __('repos.alerts.no_results') }}</p>
            </div>
        </li>
    @endforelse
</ul>

<div class="px-4 lg:px-0">
    {{ $repositories->links() }}
</div>
