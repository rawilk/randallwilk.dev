@props([
    'canonical' => null,
    'noIndex' => false,
    'description' => '',
    'title' => '',
    'ogTitle' => null,
    'ogDescription' => null,
    'ogImage' => null,
])

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

{{-- social meta --}}
<meta name="description" content="{{ $description }}" />
<meta property="og:title" content="{{ $ogTitle ?? $title }}" />
<meta property="og:description" content="{{ $ogDescription ?? $description }}" />
<meta property="og:image" content="{{ $ogImage ?? asset('images/randall.jpeg') }}" />
<meta property="og:url" content="{{ request()->getUri() }}" />
<meta property="og:type" content="website" />

@if (filled($canonical))
    <link rel="canonical" href="{{ $canonical }}" />
@endif

@if ($noIndex)
    <meta name="robots" content="noindex" />
@endif
