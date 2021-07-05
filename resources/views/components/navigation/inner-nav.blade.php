<div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
    <aside class="{{ $asideClass() }}">
        <nav class="{{ $navClass() }}">
            {{ $nav }}
        </nav>
    </aside>

    <div class="{{ $contentClass() }}">
        {{ $slot }}
    </div>
</div>
