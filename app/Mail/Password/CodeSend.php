<?php

namespace App\Mail\Password;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CodeSend extends Mailable
{
    use Queueable, SerializesModels;


    public $code;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($code)
    {

        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no-reply@medsurlink.com')
            ->subject(config('app.name') . ' Account reinitialisation code')
            ->markdown('emails.password.codeSend');
    }
}
