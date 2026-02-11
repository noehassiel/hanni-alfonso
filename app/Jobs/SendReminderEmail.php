<?php

namespace App\Jobs;

use App\Mail\ReminderMail;
use App\Models\Invitation;
use App\Models\NotificationLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Invitation $invitation,
        public string $reminderType
    ) {}

    public function handle(): void
    {
        $log = NotificationLog::create([
            'invitation_id' => $this->invitation->id,
            'type' => 'email',
            'notification_type' => $this->reminderType,
            'recipient' => $this->invitation->email,
            'status' => 'pending',
        ]);

        try {
            Mail::to($this->invitation->email)->send(new ReminderMail($this->invitation, $this->reminderType));
            $log->markAsSent();

            if ($this->reminderType === 'reminder_1') {
                $this->invitation->update(['first_reminder_sent_at' => now()]);
            } else {
                $this->invitation->update(['second_reminder_sent_at' => now()]);
            }
        } catch (\Exception $e) {
            $log->markAsFailed($e->getMessage());
            throw $e;
        }
    }
}
