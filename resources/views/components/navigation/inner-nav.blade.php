@props([
    'asideClass' => 'py-6 px-2 sm:px-6 lg:py-0 lg:px-0',
    'contentClass' => 'space-y-6 sm:px-6 lg:px-0',
    'navClass' => '',
    'nav' => '',
])

<div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
    <aside class="lg:col-span-3 {{ $asideClass }}">
        <nav class="space-y-1 {{ $navClass }}">
            {{ $nav ?? '' }}
        </nav>
    </aside>

    <div class="{{ $contentClass }} lg:col-span-9">
        {{ $slot }}
    </div>
</div>
