<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->subject = "Mail Forgot Password";
        $this->data = $data;
    }

    /**
     * Build the message
     * 
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)->replyTo("18521155@gm.uit.edu.vn", "ngocle")->view("send_mails", ['data' => $this->data]);
    }
}
