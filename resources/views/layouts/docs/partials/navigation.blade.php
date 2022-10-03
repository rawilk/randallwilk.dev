<div class="hidden lg:relative lg:block lg:flex-none">
    <div class="absolute inset-y-0 right-0 w-[50vw] bg-slate-50 dark:hidden"></div>
    <div class="sticky top-[4.5rem] -ml-0.5 h-[calc(100vh-4.5rem)] overflow-y-auto py-16 pl-0.5">
        <div class="absolute top-16 bottom-0 right-0 hidden h-12 w-px bg-gradient-to-t from-slate-800 dark:block"></div>
        <div class="absolute top-28 bottom-0 right-0 hidden w-px bg-slate-800 dark:block"></div>

        <x-docs.navigation
            :navigation="$navigation"
            :page="$page"
            :repository="$repository"
            :latest-version="$latestVersion"
            type="desktop"
            class="w-64 pr-8 xl:w-72 xl:pr-16"
        />
    </div>
</div>
