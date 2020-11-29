<?php

namespace App\Mail;

use Browser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Stevebauman\Location\Facades\Location;

class AuthorizeDeviceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var mixed
     */
    protected $authorize;

    /**
     * Create a new message instance.
     *
     * @param $authorize
     * @return void
     */
    public function __construct($authorize)
    {
        $this->authorize = $authorize;
    }

    public function saveAuthorize()
    {
        $browser = new Browser();
        $location = Location::get('81.215.237.239');//TODO: $this->authorize->ip_address

        $country = $location->countryName ?? '';
        $city = $location->cityName ?? '';

        $this->authorize->token = Str::random(64);
        $this->authorize->os = $browser->getPlatform();
        $this->authorize->browser = $browser->getBrowser();
        $this->authorize->location = $country . " / " . $city;

        $this->authorize->save();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->saveAuthorize();

        return $this
            ->view('mail.auth.authorize')
            ->with(['authorize' => $this->authorize]);
    }
}
