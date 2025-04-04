<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmail
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        try {
            Log::info("Listener triggered: Sending email to " . $event->user->email);

            Mail::to($event->user->email)->send(new WelcomeMail($event->user));

            Log::info("Email sent successfully to " . $event->user->email);
        } catch (\Exception $e) {
            Log::error("Error in SendWelcomeEmail Listener: " . $e->getMessage());
        }
    }
}
