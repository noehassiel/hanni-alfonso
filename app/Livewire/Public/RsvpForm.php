<?php

namespace App\Livewire\Public;

use App\Jobs\SendConfirmationToAdmins;
use App\Models\Invitation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class RsvpForm extends Component
{
    public Invitation $invitation;

    public int $attending = 1;

    /** @var array<int, array{id: int, name: string, attending: int, dietary_restrictions: string}> */
    public array $guests = [];

    /** Honeypot — must remain empty; bots fill it */
    public string $website = '';

    public function mount(Invitation $invitation): void
    {
        $this->invitation = $invitation;

        $this->guests = $invitation->guests->map(fn ($guest) => [
            'id' => $guest->id,
            'name' => $guest->name ?? '',
            'attending' => 1,
            'dietary_restrictions' => $guest->dietary_restrictions ?? '',
        ])->toArray();
    }

    public function submit(): void
    {
        if ($this->website !== '') {
            $this->redirect(route('rsvp.success'), navigate: false);

            return;
        }

        $key = 'rsvp:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('rate_limit', 'Demasiados intentos. Por favor espera un momento.');

            return;
        }

        RateLimiter::hit($key, 300);

        try {
            $this->validate([
                'attending' => 'required|boolean',
                'guests' => 'required|array|min:1',
                'guests.*.id' => 'required|integer',
                'guests.*.name' => 'nullable|string|max:255',
                'guests.*.attending' => 'required|boolean',
                'guests.*.dietary_restrictions' => 'nullable|string|max:500',
            ], [
                'guests.*.name.max' => 'El nombre no puede exceder 255 caracteres.',
                'guests.*.dietary_restrictions.max' => 'Las restricciones alimentarias no pueden exceder 500 caracteres.',
            ]);
        } catch (ValidationException $e) {
            $this->dispatch('haptic', type: 'error');
            throw $e;
        }

        if (! $this->attending) {
            $this->guests = array_map(function (array $guest) {
                $guest['attending'] = 0;

                return $guest;
            }, $this->guests);
        }

        $validGuestIds = $this->invitation->guests->pluck('id')->all();

        foreach ($this->guests as $guestData) {
            if (! in_array((int) $guestData['id'], $validGuestIds, true)) {
                abort(403);
            }
        }

        DB::transaction(function () {
            if ($this->attending) {
                $this->invitation->confirm();
            } else {
                $this->invitation->decline();
            }

            foreach ($this->guests as $guestData) {
                $guest = $this->invitation->guests()->findOrFail($guestData['id']);
                $guest->update([
                    'name' => $guestData['name'] ?: null,
                    'attending' => (bool) $guestData['attending'],
                    'dietary_restrictions' => $guestData['dietary_restrictions'] ?: null,
                ]);
            }

            SendConfirmationToAdmins::dispatchSync($this->invitation);
        });

        $this->dispatch('haptic', type: 'medium');

        $this->redirect(route('rsvp.success'), navigate: false);
    }

    public function render(): View
    {
        return view('livewire.public.rsvp-form');
    }
}
