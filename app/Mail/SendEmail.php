<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $emailData;

    public $form;

    public $attachData;

    public $attachName;

    public $attachType;

    /**
     * Create a new message instance.
     */
    public function __construct($emailData, $attachData, $attachName, $attachType, $form = null)
    {
        $this->emailData = $emailData;
        $this->attachData = $attachData;
        $this->form = $form;
        $this->attachName = $attachName;
        $this->attachType = $attachType;
        $this->queue = 'emails';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->from(($this->form) ? $this->form : env('MAIL_FROM_ADDRESS'))->subject($this->emailData['subject'])->html($this->emailData['content']);
        if ($this->attachData) {
            $email->attachData(base64_decode($this->attachData), $this->attachName, [
                'mime' => $this->attachType,
            ]);
        }

        return $email;
    }
}
