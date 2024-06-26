<?php

namespace App\Mail;

use App\Models\EmailDeVerificat;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrimitereCodValidareEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailDeVerificat;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmailDeVerificat $emailDeVerificat)
    {
        $this->emailDeVerificat = $emailDeVerificat;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $emailDeVerificat = $this->emailDeVerificat;

        $message = $this->markdown('mail.trimitereCodValidareEmail');

        $message->subject('Evidența persoanelor Focșani - Cod de validare a emailului');

        return $message;
    }
}
