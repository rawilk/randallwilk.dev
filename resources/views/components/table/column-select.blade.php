<x-dropdown trigger-text="{{ __('Columns') }}" with-background right>
    @foreach ($columns as $field => $label)
        <x-dropdown-item wire:click="toggleColumn('{{ $field }}')"
                         :class="'justify-between ' . ($isHidden($field) ? '' : 'dropdown-item--active')"
        >
            <span class="truncate text-left w-10/12">{{ $label }}</span>

            <span>
                @unless ($isHidden($field))
                    <x-heroicon-o-check-circle class="colorless text-green-500" />
                @else
                    <x-heroicon-o-x-circle class="colorless text-red-500" />
                @endif
            </span>
        </x-dropdown-item>
    @endforeach
</x-dropdown>
