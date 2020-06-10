<meta name="description" content="@yield('metaDescription')">

<meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">
<meta property="og:site_name" content="{{ pathinfo(config('app.url'), PATHINFO_BASENAME) }}">
<meta property="og:title" content="@hasSection('title') @yield('title') | @endif {{ config('app.name') }}">
<meta property="og:description" content="@yield('metaDescription')">
<meta property="og:url" content="{{ request()->url() }}">
<meta property="og:image" content="{{ asset('images/randall.jpg') }}">
<meta property="og:type" content="website">

<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="{{ '@' . config('site.contact.twitter.handle') }}">
<meta name="twitter:creator" content="{{ '@' . config('site.contact.twitter.handle') }}">
<meta name="twitter:title" content="@hasSection('title') @yield('title') | @endif {{ config('app.name') }}">
<meta name="twitter:description" content="@yield('metaDescription')">
<meta name="twitter:image" content="{{ asset('images/randall.jpg') }}">

<link href="{{ config('app.url') }}{{ request()->path() ? '/' . request()->path() : '' }}" rel="canonical">
