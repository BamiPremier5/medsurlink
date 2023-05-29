<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageSend extends Mailable
{
    use Queueable, SerializesModels;

    public $userEmail, $subject, $messageBody;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userEmail, $subject, $messageBody)
    {
        $this->userEmail = $userEmail;
        $this->subject = $subject;
        $this->messageBody = $messageBody;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails.messages.message-send');
    }
}
