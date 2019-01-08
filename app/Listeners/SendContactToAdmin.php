<?php

namespace App\Listeners;

use App\Events\NewContactSubmission;
use App\Mail\Frontend\Contact\NewContactSubmission as NewContactSubmissionEmail;
use Illuminate\Support\Facades\Mail;

class SendContactToAdmin
{
    /**
     * Handle the event.
     *
     * @param NewContactSubmission $event
     */
    public function handle(NewContactSubmission $event)
    {
        Mail::to(config('randallwilk.contact_email'))
            ->send(new NewContactSubmissionEmail($event->contact));
    }
}
