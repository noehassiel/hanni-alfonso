<?php

namespace App\Livewire\Admin;

use App\Jobs\SendInvitationEmail;
use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateInvitation extends Component
{
    public $group_name = '';
    public $email = '';
    public $phone = '';
    public $max_guests = 1;
    public $personal_message = '';
    public $guests = [];
    public $send_immediately = false;

    public function mount()
    {
        $this->updateGuestsList();
    }

    public function updatedMaxGuests()
    {
        $this->updateGuestsList();
    }

    private function updateGuestsList()
    {
        $currentCount = count($this->guests);
        $targetCount = (int) $this->max_guests;

        if ($targetCount > $currentCount) {
            for ($i = $currentCount; $i < $targetCount; $i++) {
                $this->guests[] = [
                    'name' => '',
                    'is_primary' => $i === 0,
                ];
            }
        } elseif ($targetCount < $currentCount) {
            $this->guests = array_slice($this->guests, 0, $targetCount);
        }
    }

    public function save()
    {
        $this->validate([
            'group_name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'max_guests' => 'required|integer|min:1|max:20',
            'personal_message' => 'nullable|string',
            'guests' => 'required|array',
            'guests.*.name' => 'nullable|string|max:255',
        ]);

        $invitation = Invitation::create([
            'group_name' => $this->group_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'max_guests' => $this->max_guests,
            'personal_message' => $this->personal_message,
            'access_code' => strtoupper(Str::random(8)),
            'magic_link_token' => Str::random(64),
        ]);

        foreach ($this->guests as $index => $guestData) {
            Guest::create([
                'invitation_id' => $invitation->id,
                'name' => $guestData['name'],
                'is_primary' => $guestData['is_primary'],
                'position' => $index + 1,
            ]);
        }

        if ($this->send_immediately && $this->email) {
            SendInvitationEmail::dispatch($invitation);
            $invitation->update(['invitation_sent_at' => now()]);
        }

        session()->flash('success', 'Invitación creada exitosamente!');

        return redirect()->to(route('admin.dashboard') . '?view=detail&id=' . $invitation->id);
    }

    public function render()
    {
        return view('livewire.admin.create-invitation');
    }
}
