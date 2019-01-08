<?php

namespace App\Mail\Frontend\Contact;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewContactSubmission extends Mailable implements ShouldQueue
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
    public $subject;

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
            if (property_exists($this, $key) && $key !== 'message') {
                $this->$key = $value;
            }

            if ($key === 'message') {
                $this->content = $value;
            }

            if ($key === 'subject') {
                $this->contactSubject = $value;
            }
        }

        $this->recipient = config('randallwilk.contact_email');

        $this->from($this->email, $this->name);

        if ($this->subject === null || trim($this->subject) === '') {
            $this->subject = 'No Subject';
            $this->contactSubject = $this->subject;
        }

        $this->subject = "New Contact - {$this->subject}";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admin-contact-email');
    }
}
