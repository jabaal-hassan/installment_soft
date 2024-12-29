<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\Auth\SendPasswordSetupEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SendPasswordSetupEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [10, 30, 60]; // Progressive backoff
    public $maxExceptions = 3;

    protected $user;
    protected $token;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function handle()
    {
        try {
            $key = 'password_setup_email_sent_' . $this->user->id . '_' . $this->token;

            // Add small delay before sending
            if ($this->attempts() > 1) {
                sleep(2);
            }

            Mail::to($this->user->email)->send(new SendPasswordSetupEmail($this->user, $this->token));
            cache()->put($key, true, now()->addHour());
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());

            if ($this->attempts() >= $this->tries) {
                throw $e;
            }

            $this->release($this->backoff[$this->attempts() - 1]);
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('Job failed finally: ' . $exception->getMessage());
    }
}
