<?php

namespace App\Http\Livewire;

use App\Models\Repository;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

class Repositories extends Component
{
    public string $search = '';
    public string $sort = '-downloads';
    public string $type = 'packages';
    public bool $filterable = true;
    public bool $highlighted = false;

    /** @var string[] */
    protected $queryString = [
        'search' => ['except' => ''],
        'sort' => ['except' => '-downloads'],
    ];

    public function getSearchPlaceholderProperty(): string
    {
        return $this->type === 'packages'
            ? __('front.repositories.package_filter_placeholder')
            : __('front.repositories.project_filter_placeholder');
    }

    public function render(): View
    {
        return view('front.livewire.repositories', [
            'repositories' => $this->getRepositories(),
        ]);
    }

    private function getRepositories(): Collection
    {
        $query = Repository::visible();

        $this->type === 'projects'
            ? $query->projects()
            : $query->packages();

        if ($this->highlighted) {
            $query->highlighted();
        }

        $query
            ->search('name', $this->search)
            ->applySort($this->sort);

        return $query->get();
    }
}
