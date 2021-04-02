<meta name="description" content="{{ $description ?? '' }}">
<meta name="og:title" content="{{ $ogTitle ?? $title ?? '' }}">
<meta name="og:description" content="{{ $ogDescription ?? $description ?? '' }}">
<meta name="og:image" content="{{ $ogImage ?? '' }}">
<meta name="og:url" content="{{ request()->getUri() }}">
<meta name="og:type" content="website">
