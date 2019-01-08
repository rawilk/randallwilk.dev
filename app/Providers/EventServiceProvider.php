<?php

namespace App\Providers;

use App\Events\NewContactSubmission;
use App\Listeners\SendContactConfirmation;
use App\Listeners\SendContactToAdmin;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        NewContactSubmission::class => [
            SendContactConfirmation::class,
            SendContactToAdmin::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
