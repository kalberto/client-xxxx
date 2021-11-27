<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SejaUmParceiroMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject, $data;

	/**
	 * Create a new message instance.
	 *
	 */
    public function __construct($subject,$data)
    {
        $this->subject = $subject;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.email_seja_um_parceiro');
    }
}
