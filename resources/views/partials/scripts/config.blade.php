@php
    $polyFills = [
        'Promise',
        'Object.assign',
        'Object.values',
        'Array.prototype.find',
        'Array.prototype.findIndex',
        'Array.prototype.includes',
        'String.prototype.includes',
        'String.prototype.startsWith',
        'String.prototype.endsWith'
    ];
@endphp

{{-- Polyfill JS features via polyfill.io --}}
<script src="https://cdn.polyfill.io/v2/polyfill.min.js?features={!! implode(',', $polyFills) !!}"></script>
