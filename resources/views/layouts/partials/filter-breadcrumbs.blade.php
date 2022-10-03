<div @class([
    'content-container',
    'mt-4' => $this->filterBreadcrumbs->isNotEmpty(),
])>
    <x-laravel-base::elements.filter-breadcrumbs :breadcrumbs="$this->filterBreadcrumbs" />
</div>
