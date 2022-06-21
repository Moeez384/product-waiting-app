<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendStatusConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $status;
    public function __construct($status)
    {
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('moeez.ahmed@unitedsol.net')
            ->subject('Status Confirmation Mail')
            ->view('Emails.statusConfrimationMail');
    }
}
