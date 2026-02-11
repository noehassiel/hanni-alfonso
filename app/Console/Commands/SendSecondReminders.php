<?php

namespace App\Console\Commands;

use App\Jobs\SendReminderEmail;
use App\Models\Invitation;
use Illuminate\Console\Command;

class SendSecondReminders extends Command
{
    protected $signature = 'reminders:send-second';
    protected $description = 'Send second reminder to confirmed guests (2 months after first reminder)';

    public function handle()
    {
        $invitations = Invitation::needsSecondReminder()->get();

        foreach ($invitations as $invitation) {
            if ($invitation->email) {
                SendReminderEmail::dispatch($invitation, 'reminder_2');
                $this->info("Second reminder queued for: {$invitation->group_name}");
            }
        }

        $this->info("Total second reminders queued: {$invitations->count()}");
    }
}
