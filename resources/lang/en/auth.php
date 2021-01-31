<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'These credentials do not match our records or your account is not verified.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.', // w/ param
    'login' => 'Login',
    'register' => 'Register',
    'remember_me' => 'Remember me',

    'verify_email' => 'Verify E-Mail',
    'verify_email_text' => 'Please follow the instructions in your e-mail to verify your account',

    'authorize_device' => 'Authorize Device',
    'authorize_device_text' => 'You login from a device or location we haven\'t seen before or for some time. Please authorize your device from mail that we sent you',
    'authorize_logged_out' => 'You are logged out of system, please follow the link we sent before :sent_before minutes to authorize your device, the link will be valid for :valid_for hour', // w/ param
    'authorize_token_expired' => 'Token is invalid or expired',

    'wait_for_redirect' => 'Successful, please wait you\'re redirecting.',
    'registered_needs_verification' => '<span>You\'re registered successfully. <b>*Please verify your e-mail from the mail we sent you.</b></span>',

    'subscribe_accept_text' => 'I want to be notified by email when new article posted'
];
