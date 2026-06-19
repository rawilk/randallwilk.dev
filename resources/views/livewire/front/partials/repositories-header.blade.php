<div class="flex flex-col md:flex-row md:items-end md:justify-between gap-8 mb-12 w-full">
    <h2 class="text-[40px] font-bold leading-[0.9] uppercase">
        @if ($type === 'packages')
            All<br>packages
        @else
            All<br>projects
        @endif
    </h2>

    <div class="md:max-w-md w-full relative">
        <x-forms.front-search-input
            :placeholder="$this->repositoryType->searchPlaceholderText()"
            :value="$search"
            x-model="query"
        />
    </div>
</div>
