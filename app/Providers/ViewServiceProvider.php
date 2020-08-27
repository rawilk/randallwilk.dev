<?php

namespace App\Providers;

use Carbon\Laravel\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blade::directive('svg', fn ($expression) => "<?php echo svg({$expression}); ?>");
    }
}
