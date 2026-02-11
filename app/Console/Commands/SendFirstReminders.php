<?php

namespace App\Console\Commands;

use App\Jobs\SendReminderEmail;
use App\Models\Invitation;
use Illuminate\Console\Command;

class SendFirstReminders extends Command
{
    protected $signature = 'reminders:send-first';
    protected $description = 'Send first reminder to confirmed guests (2 months after confirmation)';

    public function handle()
    {
        $invitations = Invitation::needsFirstReminder()->get();

        foreach ($invitations as $invitation) {
            if ($invitation->email) {
                SendReminderEmail::dispatch($invitation, 'reminder_1');
                $this->info("First reminder queued for: {$invitation->group_name}");
            }
        }

        $this->info("Total first reminders queued: {$invitations->count()}");
    }
}
