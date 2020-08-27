<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<title>{!! $title ?? '' !!} | Randall Wilk</title>

<meta name="description" content="{{ $description ?? '' }}">
<meta name="og:title" content="{{ $ogTitle ?? $title ?? '' }}">
<meta name="og:description" content="{{ $ogDescription ?? $description ?? '' }}">
<meta name="og:image" content="{{ $ogImage ?? '' }}">
<meta name="og:url" content="{{ request()->getUri() }}">
<meta name="og:type" content="website">
