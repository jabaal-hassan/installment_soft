<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;

class SendPasswordSetupEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;
    public $setupUrl;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;

        // Get frontend URL with strict default
        $frontendUrl = env('FRONTEND_URL');

        // Force a default if env is empty or malformed
        if (empty($frontendUrl) || !parse_url($frontendUrl)) {
            $frontendUrl = 'http://localhost:5173';
            Log::info('Using default frontend URL: ' . $frontendUrl);
        }

        // Ensure URL has protocol
        if (!preg_match('~^(?:f|ht)tps?://~i', $frontendUrl)) {
            $frontendUrl = 'http://' . $frontendUrl;
        }

        // Clean the URL
        $frontendUrl = rtrim($frontendUrl, '/');

        // Debug line
        Log::info('Frontend URL: ' . $frontendUrl);

        $this->setupUrl = $frontendUrl . '/password-setup?email=' . urlencode($user->email) . '&token=' . $token;

        // Debug line
        Log::info('Final Setup URL: ' . $this->setupUrl);
    }

    public function build()
    {
        Log::info('Building email with setup URL: ' . $this->setupUrl);

        return $this->view('emails.password_setup')
            ->subject('Set up your password');
    }
}
