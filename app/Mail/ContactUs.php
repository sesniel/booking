<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUs extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $requestData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $requestData)
    {
        $this->requestData = $requestData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->requestData['email'], $this->requestData['name'])
            ->subject($this->requestData['reason'])
            ->view('emails.contact-us');
    }
}
