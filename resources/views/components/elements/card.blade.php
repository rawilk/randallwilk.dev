<div {{ $attributes->merge(['class' => 'bg-white overflow-hidden shadow ' . ($roundedOnMobile ? 'rounded-lg' : 'lg:rounded-lg')]) }}>
    @if ($header)
        <div class="bg-blue-gray-50 px-4 py-5 sm:px-6">
            {{ $header }}
        </div>
    @endif

    <div class="{{ $flush ? '' : 'px-4 py-5 sm:p-6' }}">
        {{ $slot }}
    </div>

    @if ($footer)
        <div class="bg-blue-gray-50 px-4 py-4 sm:px-6">
            {{ $footer }}
        </div>
    @endif
</div>
