<?php

namespace App\Livewire\Public;

use App\Mail\OtpMail;
use App\Models\Invitation;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class OtpVerification extends Component
{
    public Invitation $invitation;

    public string $step = 'email';

    public string $email = '';

    public string $otp = '';

    public string $otpError = '';

    public string $sentTo = '';

    public function mount(Invitation $invitation): void
    {
        $this->invitation = $invitation;
    }

    public function sendOtp(): void
    {
        $this->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Por favor ingresa tu correo electrónico.',
            'email.email' => 'Ingresa un correo electrónico válido.',
        ]);

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        try {
            $this->invitation->update([
                'otp_code' => $otp,
                'otp_expires_at' => now()->addMinutes(10),
            ]);

            Mail::to($this->email)->send(new OtpMail($this->invitation, $otp));

            $this->sentTo = $this->email;
            $this->step = 'otp';
            $this->dispatch('toast', message: 'Código enviado a '.$this->email);
        } catch (\Exception $e) {
            $this->addError('email', 'No pudimos enviar el código. Intenta de nuevo.');
        }
    }

    public function verifyOtp(): void
    {
        $this->otpError = '';

        $this->validate([
            'otp' => 'required|digits:6',
        ], [
            'otp.required' => 'Ingresa el código de 6 dígitos.',
            'otp.digits' => 'El código debe tener exactamente 6 dígitos.',
        ]);

        $invitation = $this->invitation;

        if (
            $invitation->otp_code !== $this->otp ||
            now()->isAfter($invitation->otp_expires_at)
        ) {
            $this->otpError = 'Código incorrecto o expirado. Intenta de nuevo.';

            return;
        }

        $invitation->update([
            'email' => $this->email ?: $invitation->email,
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        session()->put('otp_verified_'.$invitation->id, true);

        $this->dispatch('otp-verified');

        $this->redirect(route('invitation.show', $invitation->magic_link_token));
    }

    public function resend(): void
    {
        $this->step = 'email';
        $this->otp = '';
        $this->otpError = '';
    }

    public function render()
    {
        return view('livewire.public.otp-verification');
    }
}
