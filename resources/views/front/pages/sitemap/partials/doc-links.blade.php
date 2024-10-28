<section class="section pt-0">
    <h2 class="text-2xl text-slate-900 font-semibold pt-6 mb-4 border-t border-slate-300">{{ __('Docs') }}</h2>

    <ul class="space-y-10 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:gap-y-12 sm:space-y-0 lg:grid-cols-3 lg:gap-x-8">
        <x-front.sitemap-link-section>
            @foreach ($repositories as $repository)
                <x-front.sitemap-link
                    :url="action([\App\Http\Controllers\Docs\DocsController::class, 'repository'], $repository->slug)"
                    :spa="false"
                >
                    {{ $repository->slug }}
                </x-front.sitemap-link>
            @endforeach
        </x-front.sitemap-link-section>
    </ul>
</section>
