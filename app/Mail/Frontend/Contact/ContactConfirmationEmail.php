<?php

namespace App\Mail\Frontend\Contact;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactConfirmationEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The name of the sender.
     *
     * @var string
     */
    public $name;

    /**
     * The email of the sender.
     *
     * @var string
     */
    public $email;

    /**
     * The subject of the inquiry.
     *
     * @var string
     */
    public $contactSubject;

    /**
     * The message of the inquiry.
     *
     * @var string
     */
    public $content;

    /**
     * The recipient of the email.
     *
     * @var string
     */
    public $recipient;

    /**
     * Create a new message instance.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key) && $key !== 'message' && $key !== 'subject') {
                $this->$key = $value;
            }

            if ($key === 'message') {
                $this->content = $value;
            }

            if ($key === 'subject') {
                $this->contactSubject = $value;
            }
        }

        if ($this->contactSubject === null || trim($this->contactSubject) === '') {
            $this->contactSubject = 'No Subject';
        }

        $this->subject = 'Contact Confirmation';

        $this->recipient = $this->email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.contact-confirmation');
    }
}
