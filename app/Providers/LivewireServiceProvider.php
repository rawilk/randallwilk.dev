<?php

namespace App\Providers;

use App\Http\Livewire\Repositories;
use Illuminate\Support\ServiceProvider;
use Livewire\Component;
use Livewire\Livewire;

final class LivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootMacros();
    }

    public function register(): void
    {
        Livewire::component('repositories', Repositories::class);
    }

    private function bootMacros(): void
    {
        Component::macro('notify', function (string|null $message) {
            /** @var \Livewire\Component $this */
            $this->dispatchBrowserEvent('notify', $message);
        });
    }
}
