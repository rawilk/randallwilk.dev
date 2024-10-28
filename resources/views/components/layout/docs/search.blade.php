@props(['repository', 'alias'])

<div
    x-data="search(@js([
        'project' => $repository->slug,
        'version' => $alias->slug,
    ]))"
>
    <div id="docsearch" class="group"></div>
</div>
