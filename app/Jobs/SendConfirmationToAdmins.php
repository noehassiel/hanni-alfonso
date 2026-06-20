<?php

namespace App\Jobs;

use App\Mail\ConfirmationToAdminMail;
use App\Models\Invitation;
use App\Models\NotificationLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Resend\Laravel\Facades\Resend;

class SendConfirmationToAdmins implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Invitation $invitation
    ) {}

    public function handle(): void
    {
        $isCancellation = $this->invitation->status === 'declined';

        $log = NotificationLog::create([
            'invitation_id' => $this->invitation->id,
            'type' => 'email',
            'notification_type' => $isCancellation ? 'cancellation_to_admin' : 'confirmation_to_admin',
            'recipient' => 'admins',
            'status' => 'pending',
        ]);

        try {
            Resend::emails()->send([
                'from' => config('mail.from.name').' <'.config('mail.from.address').'>',
                'to' => ['hannia.actionschool@gmail.com', 'denisse-bandala@hotmail.com', 'azamar.neria@gmail.com'],
                'subject' => $isCancellation ? 'Cancelación de Asistencia' : 'Nueva Confirmación de Asistencia',
                'html' => (new ConfirmationToAdminMail($this->invitation))->render(),
            ]);
            $log->markAsSent();
        } catch (\Exception $e) {
            $log->markAsFailed($e->getMessage());
            throw $e;
        }
    }
}
