<?php

namespace App\Listeners;

use App\Events\NewContactSubmission;
use App\Mail\Frontend\Contact\ContactConfirmationEmail;
use Illuminate\Support\Facades\Mail;

class SendContactConfirmation
{
    /**
     * Handle the event.
     *
     * @param NewContactSubmission $event
     */
    public function handle(NewContactSubmission $event)
    {
        try {
            Mail::to($event->contact['email'])
                ->send(new ContactConfirmationEmail($event->contact));
        } catch (\Exception $e) {}
    }
}
