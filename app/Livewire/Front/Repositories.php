<?php

declare(strict_types=1);

namespace App\Livewire\Front;

use App\Enums\RepositorySort;
use App\Enums\RepositoryType;
use App\Models\Repository;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * @property-read string $searchPlaceholder
 * @property-read Htmlable $noResultsText
 * @property-read RepositoryType $repositoryType
 * @property-read int $total
 * @property-read bool $hasMore
 * @property-read Collection<int, RepositorySort> $availableSorts
 */
class Repositories extends Component
{
    #[Locked]
    public string $type = 'packages';

    #[Locked]
    public bool $filterable = true;

    #[Url(as: 'q')]
    public ?string $search = '';

    #[Url]
    public string $sort = '-downloads';

    #[Locked]
    public int $pageSize = 9;

    public Collection $repositories;

    #[Computed]
    public function searchPlaceholder(): string
    {
        return $this->type === 'packages'
            ? 'Search packages...'
            : 'Search projects...';
    }

    #[Computed]
    public function noResultsText(): Htmlable
    {
        return new HtmlString(Blade::render(<<<'HTML'
            {{ $line1 }}<br>Try checking back later.
        HTML, [
            'line1' => $this->type === 'packages'
                ? "It appears there isn't a package I've created for that."
                : "It appears there isn't a project I've created for that.",
        ]));
    }

    #[Computed]
    public function repositoryType(): RepositoryType
    {
        return $this->type === 'packages'
            ? RepositoryType::Package
            : RepositoryType::Project;
    }

    #[Computed]
    public function total(): int
    {
        return Repository::query()
            ->visible()
            ->byType($this->repositoryType)
            ->search($this->search)
            ->count();
    }

    #[Computed]
    public function hasMore(): bool
    {
        return $this->repositories->count() < $this->total;
    }

    #[Computed]
    public function availableSorts(): Collection
    {
        return collect(RepositorySort::cases())
            ->filter(function (RepositorySort $case) {
                if ($this->repositoryType !== RepositoryType::Project) {
                    return true;
                }

                return $case !== RepositorySort::Downloads;
            });
    }

    public function mount(): void
    {
        $this->repositories = collect();
        $this->loadRepositories();

        if ($this->repositoryType === RepositoryType::Project && $this->sort === '-downloads') {
            $this->sort = 'name';
        }
    }

    public function render(): View
    {
        return view('livewire.front.repositories');
    }

    public function updatedSearch(): void
    {
        $this->repositories = collect();
        $this->loadRepositories();
    }

    public function updatedSort(): void
    {
        $this->repositories = collect();
        $this->loadRepositories();
    }

    public function loadMore(): void
    {
        $this->loadRepositories(loadingMore: true);
    }

    protected function loadRepositories(bool $loadingMore = false): void
    {
        Repository::query()
            ->visible()
            ->byType($this->repositoryType)
            ->when(
                $loadingMore,
                fn (Builder $query) => $query->offset($this->repositories->count()),
            )
            ->search($this->search)
            ->applySort($this->sort)
            ->limit($this->pageSize)
            ->get()
            ->each(function (Repository $repository) {
                $this->repositories->push($repository);
            });
    }
}
