<a href="{{ $url }}"
    @class([
        '-m-3 block rounded-md p-3',
        'bg-gray-100' => $active,
        'hover:bg-gray-50' => ! $active,
    ])>
    <span class="block text-base font-medium text-slate-900">{{ $label }}</span>

    @if ($description ?? '')
        <span class="block font-normal mt-1 text-sm text-slate-500">{{ $description }}</span>
    @endif
</a>
