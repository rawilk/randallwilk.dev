@props([
    'functionName' => 'watchFilters',
    'event' => 'filters-hidden',
])

<div x-data x-init="{{ $functionName }}($wire)"></div>

@once
    @push('js')
        <script>
            function {{ $functionName }}($wire) {
                $wire.on('{{ $event }}', () => {
                    document.querySelectorAll('.flatpickr-calendar')
                        .forEach(node => node.remove());
                });
            }
        </script>
    @endpush
@endonce
