@props(['sectionTitle', 'title', 'repositoryName' => null])

<header
    {{ $attributes->class(['mb-9 space-y-1']) }}
>
    @if (filled($repositoryName))
        <p class="font-display text-slate-600 dark:text-white font-bold mb-5">
            {{ $repositoryName }}
        </p>
    @endif

    <p class="font-display text-sm font-medium text-sky-500">
        {{ $sectionTitle }}
    </p>

    <h1 class="font-display text-3xl tracking-tight text-slate-900 dark:text-white">
        {{ $title }}
    </h1>
</header>
