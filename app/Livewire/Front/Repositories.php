<?php

declare(strict_types=1);

namespace App\Livewire\Front;

use App\Enums\RepositoryType;
use App\Models\Repository;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * @property-read RepositoryType $repositoryType
 */
class Repositories extends Component
{
    #[Locked]
    public string $type = 'packages';

    #[Url(as: 'q')]
    public ?string $search = '';

    #[Computed]
    public function repositoryType(): RepositoryType
    {
        return $this->type === 'packages'
            ? RepositoryType::Package
            : RepositoryType::Project;
    }

    public function render(): View
    {
        $allRepositories = $this->getRepositories();

        $allRepositoryHaystacks = $allRepositories
            ->map(fn (Repository $repository): string => Str::lower(($repository->name ?? '') . ' ' . ($repository->description ?? '')))
            ->values();

        return view('livewire.front.repositories', [
            'allRepositories' => $allRepositories,
            'allRepositoryHaystacks' => $allRepositoryHaystacks,
        ]);
    }

    protected function getRepositories(): Collection
    {
        $columnsToSelect = $this->repositoryType === RepositoryType::Package
            ? ['name', 'downloads', 'description']
            : ['name', 'description'];

        return Repository::query()
            ->visible()
            ->byType($this->repositoryType)
            ->orderBy('name')
            ->get($columnsToSelect);
    }
}
