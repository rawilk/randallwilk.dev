<x-form-error name="{{ $inputName }}" tag="div">
    @php($inputError = $component->messages($errors)[0] ?? '')

    <x-alert type="error">
        <p class="alert__text">{{ $inputError }}</p>
    </x-alert>
</x-form-error>
