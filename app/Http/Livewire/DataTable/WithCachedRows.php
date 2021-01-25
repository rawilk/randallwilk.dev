<?php

namespace App\Http\Livewire\DataTable;

use Illuminate\Support\Facades\Cache;

/** @mixin \Livewire\Component */
trait WithCachedRows
{
    protected bool $useCache = false;

    public function useCachedRows(): void
    {
        $this->useCache = true;
    }

    public function cacheQueryKey(): string
    {
        return $this->id;
    }

    public function cacheTtl(): \Illuminate\Support\Carbon
    {
        return now()->addMinutes(10);
    }

    public function cache($callback)
    {
        $cacheKey = $this->cacheQueryKey();

        if ($this->useCache && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $result = $callback();

        Cache::put($cacheKey, $result, $this->cacheTtl());

        return $result;
    }
}
