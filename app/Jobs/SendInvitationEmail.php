<?php

namespace App\Jobs;

use App\Mail\InvitationMail;
use App\Models\Invitation;
use App\Models\NotificationLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendInvitationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Invitation $invitation
    ) {}

    public function handle(): void
    {
        $log = NotificationLog::create([
            'invitation_id' => $this->invitation->id,
            'type' => 'email',
            'notification_type' => 'invitation',
            'recipient' => $this->invitation->email,
            'status' => 'pending',
        ]);

        try {
            Mail::to($this->invitation->email)->send(new InvitationMail($this->invitation));
            $log->markAsSent();
        } catch (\Exception $e) {
            $log->markAsFailed($e->getMessage());
            throw $e;
        }
    }
}
