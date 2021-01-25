<x-dropdown trigger-text="{{ __('labels.column_filter') }}" with-background right>
    @foreach ($this->hideableColumns as $field => $label)
        <x-dropdown-item wire:click="toggleColumn('{{ $field }}')"
                         :class="'justify-between ' . ($this->isHidden($field) ? '' : 'dropdown-item--active')"
        >
            <span class="truncate text-left w-10/12">{{ $label }}</span>

            <span>
                @unless($this->isHidden($field))
                    <x-heroicon-o-check-circle class="colorless text-success-500" />
                @else
                    <x-heroicon-o-x-circle class="colorless text-danger-500" />
                @endif
            </span>
        </x-dropdown-item>
    @endforeach
</x-dropdown>
