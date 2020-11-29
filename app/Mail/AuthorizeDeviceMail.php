<?php

namespace App\Mail;

use Browser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Stevebauman\Location\Facades\Location;

/**
 * Class AuthorizeDevice
 * @package App\Mail
 */
class AuthorizeDeviceMail extends Mailable
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

    /**
     * @return mixed
     */
    public function setBrowser()
    {
        $browser = new Browser();
        $this->authorize->browser = $browser->getBrowser();

        return $this;
    }

    /**
     * @return mixed
     */
    public function setToken()
    {
        $this->authorize->token = Str::random(64);

        return $this;
    }

    /**
     * @return mixed
     */
    public function setLocation()
    {
        $location = Location::get('81.215.237.239');//$this->authorize->ip_address
        $country = $location->countryName ?? '';
        $city = $location->cityName ?? '';
        $this->authorize->location = $country . " / " . $city;

        return $this;
    }

    /**
     * @return mixed
     */
    public function setPlatform()
    {
        $browser = new Browser();
        $this->authorize->os = $browser->getPlatform();

        return $this;
    }

    public function saveAuthorize()
    {
        $this->authorize->save();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this
            ->setBrowser()
            ->setToken()
            ->setLocation()
            ->setPlatform()
            ->saveAuthorize();

        return $this
            ->view('mail.auth.authorize')
            ->with(['authorize' => $this->authorize]);
    }
}
