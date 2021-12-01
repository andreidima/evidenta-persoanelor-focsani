<?php

namespace App\Mail;

use App\Models\Programare;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProgramareEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $programare;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Programare $programare)
    {
        $this->programare = $programare;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $programare = $this->programare;

        $message = $this->markdown('mail.programare-email');

        $message->subject('Evidența persoanelor Focșani - Programare');

        return $message;
    }
}
