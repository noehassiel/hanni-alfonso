<?php

namespace App\Livewire\Admin;

use App\Jobs\SendInvitationEmail;
use App\Models\Invitation;
use Livewire\Component;

class InvitationDetail extends Component
{
    public Invitation $invitation;

    public function mount(Invitation $invitation)
    {
        $this->invitation = $invitation->load(['guests', 'notificationLogs']);
    }

    public function sendInvitation()
    {
        if (! $this->invitation->email) {
            $this->dispatch('notify', type: 'error', content: 'Esta invitación no tiene correo registrado.');

            return;
        }

        SendInvitationEmail::dispatchSync($this->invitation);
        $this->invitation->update(['invitation_sent_at' => now()]);

        $this->dispatch('notify', type: 'success', content: 'Invitación enviada exitosamente!');
        $this->invitation->refresh();
    }

    public function resendInvitation()
    {
        $this->sendInvitation();
    }

    public function copyMagicLink(): void
    {
        $this->dispatch('copy-to-clipboard', text: $this->invitation->magic_link);
        $this->dispatch('notify', type: 'success', content: 'Magic link copiado!');
    }

    public function copyWhatsappMessage(): void
    {
        $this->dispatch('copy-to-clipboard', text: $this->getWhatsappMessage());
        $this->dispatch('notify', type: 'success', content: 'Mensaje de WhatsApp copiado!');
    }

    public function getWhatsappMessage(): string
    {
        $name = $this->invitation->group_name;
        $link = $this->invitation->magic_link;
        $guests = $this->invitation->max_guests;

        return "Hola *{$name}*! 💐\n\n"
            ."Con mucho cariño te invitamos a celebrar nuestra boda 💒\n\n"
            ."📅 *24 de Octubre, 2025*\n"
            ."👥 Lugares reservados: *{$guests}*\n\n"
            ."Confirma tu asistencia aquí 👇\n"
            ."{$link}\n\n"
            ."Con amor,\n"
            .'*Hanni & Alfonso* 🤍';
    }

    public function render()
    {
        return view('livewire.admin.invitation-detail');
    }
}
