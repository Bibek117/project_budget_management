<?php

namespace App\Listeners;

use App\Events\ProjectAssigned;
use App\Mail\ProjectAssignedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAssignedConfirmation
{
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
    public function handle(ProjectAssigned $event): void
    {
        $user = $event->user;
        Mail::to($user->email)->send(new ProjectAssignedMail($user));
    }
}
