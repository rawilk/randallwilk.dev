<section id="resources" class="section pt-0 pb-8">
    <div class="wrap">

        <p class="title-2xl font-sans">A few suggestions</p>

        <x-elements.action-item-list>

            {{-- home --}}
            <x-elements.action-item href="{!! route('home') !!}" icon="heroicon-o-home">
                Home page
            </x-elements.action-item>

            {{-- open source packages --}}
            <x-elements.action-item href="{!! route('open-source.packages') !!}" icon="heroicon-o-tag">
                Open source packages
            </x-elements.action-item>

            {{-- contact --}}
            <x-elements.action-item href="{!! route('contact') !!}" icon="heroicon-o-chat">
                Contact
            </x-elements.action-item>

            {{-- docs --}}
            <x-elements.action-item href="{!! route('docs') !!}" icon="heroicon-o-document-text">
                Docs
            </x-elements.action-item>

        </x-elements.action-item-list>
    </div>
</section>
