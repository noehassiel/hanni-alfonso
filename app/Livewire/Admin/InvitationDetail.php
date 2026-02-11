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
        if (!$this->invitation->email) {
            session()->flash('error', 'No email address provided for this invitation.');
            return;
        }

        SendInvitationEmail::dispatch($this->invitation);
        $this->invitation->update(['invitation_sent_at' => now()]);

        session()->flash('success', 'Invitation sent successfully!');
        $this->invitation->refresh();
    }

    public function resendInvitation()
    {
        $this->sendInvitation();
    }

    public function copyMagicLink(): void
    {
        $this->dispatch('copy-to-clipboard', text: $this->invitation->magic_link);
        session()->flash('success', 'Magic link copiado!');
    }

    public function copyWhatsappMessage(): void
    {
        $this->dispatch('copy-to-clipboard', text: $this->getWhatsappMessage());
        session()->flash('success', 'Mensaje de WhatsApp copiado!');
    }

    public function getWhatsappMessage(): string
    {
        $name = $this->invitation->group_name;
        $link = $this->invitation->magic_link;
        $guests = $this->invitation->max_guests;

        return "Hola *{$name}*! 💐\n\n"
            . "Con mucho cariño te invitamos a celebrar nuestra boda 💒\n\n"
            . "📅 *24 de Octubre, 2025*\n"
            . "👥 Lugares reservados: *{$guests}*\n\n"
            . "Confirma tu asistencia aquí 👇\n"
            . "{$link}\n\n"
            . "Con amor,\n"
            . "*Hanni & Alfonso* 🤍";
    }

    public function render()
    {
        return view('livewire.admin.invitation-detail');
    }
}
