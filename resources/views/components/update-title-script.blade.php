@props(['function' => 'onNameUpdate', 'action'])

{{-- Quick & dirty div so we can access the "$wire" property --}}
<div x-data
     x-init="{{ $function }}($wire)"
>
</div>

@push('js')
<script>
    function {{ $function }}($wire) {
        $wire.on('{{ $action }}', () => {
            @if ($slot->isEmpty())
                const newTitle = `Edit ${$wire.state['name']}`;
            @else
                {{ $slot }}
            @endif

            updatePageTitle(newTitle);
        });
    }
</script>
@endpush
