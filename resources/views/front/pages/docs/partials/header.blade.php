<header class="mb-9 space-y-1">
    <div class="mb-5 hidden sm:block">
        <x-breadcrumbs
            breadcrumbs="docs.repository"
            :params="[$repository, $alias, $page, $sectionTitle]"
        />
    </div>

    <p class="font-display text-sm font-medium text-sky-500">
        {{ $sectionTitle }}
    </p>
    <h1 class="font-display text-3xl tracking-tight text-slate-900 dark:text-white">{{ $page->title }}</h1>
</header>
