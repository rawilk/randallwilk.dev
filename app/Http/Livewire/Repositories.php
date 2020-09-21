<?php

namespace App\Http\Livewire;

use App\Models\Repository;
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

    public function render()
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
            ->search($this->search)
            ->applySort($this->sort);

        return $query->get();
    }
}
