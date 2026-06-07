<?php

namespace App\Jobs;

use App\Mail\ConfirmationToAdminMail;
use App\Models\User;
use App\Models\Invitation;
use App\Models\NotificationLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendConfirmationToAdmins implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Invitation $invitation
    ) {}

    public function handle(): void
    {
        $admins = User::where('is_admin', true)->get();

        foreach ($admins as $admin) {
            $log = NotificationLog::create([
                'invitation_id' => $this->invitation->id,
                'type' => 'email',
                'notification_type' => 'confirmation_to_admin',
                'recipient' => $admin->email,
                'status' => 'pending',
            ]);

            try {
                Mail::to($admin->email)->send(new ConfirmationToAdminMail($this->invitation));
                $log->markAsSent();
            } catch (\Exception $e) {
                $log->markAsFailed($e->getMessage());
            }
        }
    }
}
