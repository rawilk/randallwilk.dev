<div>
    <div class="space-y-4 py-4">
        {{-- top bar --}}
        @include('livewire.admin.repositories.index.partials.topbar')

        {{-- results --}}
        @include('livewire.admin.repositories.index.partials.results')
    </div>

    {{-- delete modal --}}
    @include('livewire.admin.repositories.partials.confirm-delete', ['repository' => $deleting])

    {{-- edit form --}}
    @include('livewire.admin.repositories.partials.form', ['repository' => $editing])
</div>
