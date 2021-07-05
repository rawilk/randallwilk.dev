@props(['action'])

@push('js')
<script>
    @this.on('{{ $action }}', () => {
        @if ($slot->isEmpty())
            const newTitle = `Edit ${@this.state['name']}`;
        @else
            {{ $slot }}
        @endif

        updatePageTitle(newTitle);
    });
</script>
@endpush
