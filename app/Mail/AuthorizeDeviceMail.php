<?php

namespace App\Mail;

use App\Models\Authorize;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AuthorizeDeviceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @param $authorize
     * @return void
     */
    public function __construct(protected Authorize $authorize) {}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->view('mail.auth.authorize')
            ->with(['authorize' => $this->authorize]);
    }
}
