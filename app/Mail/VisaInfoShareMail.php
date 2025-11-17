<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VisaInfoShareMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Visa Information Shared - ' . ($this->mailData['visa_category'] ?? 'Visa Details'))
            ->view('emails.visa-info-share')
            ->with([
                'name' => $this->mailData['name'] ?? '',
                'email' => $this->mailData['email'] ?? '',
                'contact' => $this->mailData['contact'] ?? '',
                'visa_category' => $this->mailData['visa_category'] ?? 'Visa Information',
                'city' => $this->mailData['city'] ?? '',
                'service_charges' => $this->mailData['service_charges'] ?? 'To be confirmed',
                'additional_info' => $this->mailData['additional_info'] ?? '',
            ]);
    }
}
