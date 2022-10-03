@props([
    'content' => '',
])

<section id="banner" role="banner" {{ $attributes->class('banner') }}>
    <div class="wrap">
        <h1 class="banner-slogan">{{ $slot }}</h1>

        {{ $content }}
    </div>
</section>
