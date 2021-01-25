@props([
    'label' => false,
    'value' => false,
])

<div x-data="{
        value: {{ json_encode($value) }},
     }"
>
    <button x-on:click="value = ! value"
            type="button"
            x-bind:aria-pressed="JSON.stringify(value)"
            {{ $attributes->merge(['class' => 'flex-shrink-0 group relative rounded-full inline-flex items-center justify-center h-5 w-10 cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500']) }}
    >
        @if ($label)
            <span class="sr-only">{{ $label }}</span>
        @endif

        {{-- background --}}
        <span aria-hidden="true"
              class="absolute h-4 w-9 mx-auto rounded-full transition-colors ease-in-out duration-200"
              x-bind:class="{ 'bg-success-400': value, 'bg-gray-200': ! value  }"
        >
        </span>

        {{-- knob --}}
        <span aria-hidden="true"
              class="absolute left-0 inline-block h-5 w-5 border border-gray-200 rounded-full bg-white shadow transform ring-0 transition-transform ease-in-out duration-200"
              x-bind:class="{ 'translate-x-5': value, 'translate-x-0': ! value }"
        >
        </span>
    </button>
</div>
