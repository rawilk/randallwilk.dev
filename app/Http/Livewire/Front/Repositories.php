<?php

declare(strict_types=1);

namespace App\Http\Livewire\Front;

use App\Enums\RepositorySort;
use App\Models\GitHub\Repository;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;

/**
 * @property-read string $noResultsText
 * @property-read string $searchPlaceholder
 */
final class Repositories extends Component
{
    public string $search = '';

    public string $type = 'package';

    public bool $filterable = true;

    public string $sort = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'sort' => ['except' => '-downloads'],
    ];

    public function getSearchPlaceholderProperty(): string
    {
        return $this->type === 'package'
            ? __('front.open_source.packages.search_placeholder')
            : __('front.open_source.projects.search_placeholder');
    }

    public function getNoResultsTextProperty(): string
    {
        $text = $this->type === 'package'
            ? __('front.open_source.packages.no_results')
            : __('front.open_source.projects.no_results');

        return Str::inlineMarkdown($text);
    }

    public function getRowsQueryProperty()
    {
        $query = Repository::visible()
            ->when($this->search, fn ($query, $search) => $query->modelSearch(['name', 'scoped_name'], $search))
            ->byType($this->type);

        $query->applySort($this->sort);

        return $query;
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->get();
    }

    public function mount(): void
    {
        $this->sort = $this->type === 'package'
            ? RepositorySort::Downloads->selectValue()
            : RepositorySort::RepositoryCreatedAt->selectValue();
    }

    public function render(): View
    {
        return view('livewire.front.repositories', [
            'repositories' => $this->rows,
        ]);
    }
}
