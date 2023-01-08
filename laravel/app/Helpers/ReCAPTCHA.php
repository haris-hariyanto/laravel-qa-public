<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class ReCAPTCHA
{
    private $userResponse;
    private $grcSecretKey;
    private $url = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct($userResponse)
    {
        $this->userResponse = $userResponse;
        $this->grcSecretKey = config('services.grecaptcha.secret_key');
    }

    public function verify()
    {
        $response = Http::asForm()->post($this->url, [
            'secret' => $this->grcSecretKey,
            'response' => $this->userResponse,
        ]);
        return $response->json('success');
    }
}