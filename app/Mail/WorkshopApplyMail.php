<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WorkshopApplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
   
    public function __construct($data)
    {
        $this->data=$data;
    }

    public function build()
    {
        return $this->subject($this->data['subject'])
            ->view('emails.workshop-appy-mail');
    }
}
