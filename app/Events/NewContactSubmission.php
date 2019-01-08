<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class NewContactSubmission
{
    use Dispatchable, SerializesModels;

    /**
     * @var array
     */
    public $contact;

    /**
     * Create a new event instance.
     *
     * @param array $contact
     */
    public function __construct(array $contact)
    {
        $this->contact = $contact;
    }
}
