<?php

namespace Tests\Feature\Public;

use App\Jobs\SendConfirmationToAdmins;
use App\Livewire\Public\RsvpForm;
use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class RsvpFormTest extends TestCase
{
    use RefreshDatabase;

    private function makeInvitation(int $guestCount = 2): Invitation
    {
        $invitation = Invitation::create([
            'group_name' => 'Familia Test',
            'access_code' => strtoupper(substr(md5(uniqid()), 0, 8)),
            'magic_link_token' => Str::random(64),
            'email' => 'test@example.com',
            'max_guests' => $guestCount,
            'status' => 'pending',
        ]);

        for ($i = 0; $i < $guestCount; $i++) {
            Guest::create([
                'invitation_id' => $invitation->id,
                'name' => 'Invitado '.($i + 1),
                'is_primary' => $i === 0,
                'attending' => null,
                'position' => $i + 1,
            ]);
        }

        return $invitation->fresh('guests');
    }

    public function test_component_mounts_with_guests(): void
    {
        $invitation = $this->makeInvitation(2);

        Livewire::test(RsvpForm::class, ['invitation' => $invitation])
            ->assertSet('attending', 1)
            ->assertCount('guests', 2);
    }

    public function test_confirming_attendance_updates_invitation_and_guests(): void
    {
        Queue::fake();
        $invitation = $this->makeInvitation(2);
        $guests = $invitation->guests;

        Livewire::test(RsvpForm::class, ['invitation' => $invitation])
            ->set('attending', 1)
            ->set('guests.0.name', 'Juan García')
            ->set('guests.0.attending', 1)
            ->set('guests.0.dietary_restrictions', '')
            ->set('guests.1.name', 'María García')
            ->set('guests.1.attending', 1)
            ->set('guests.1.dietary_restrictions', 'Vegetariana')
            ->call('submit')
            ->assertRedirect(route('rsvp.success'));

        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
            'status' => 'confirmed',
        ]);

        $this->assertDatabaseHas('guests', [
            'id' => $guests[0]->id,
            'name' => 'Juan García',
            'attending' => true,
        ]);

        $this->assertDatabaseHas('guests', [
            'id' => $guests[1]->id,
            'name' => 'María García',
            'attending' => true,
            'dietary_restrictions' => 'Vegetariana',
        ]);

        Queue::assertPushed(SendConfirmationToAdmins::class);
    }

    public function test_declining_attendance_sets_invitation_as_declined(): void
    {
        Queue::fake();
        $invitation = $this->makeInvitation(1);
        $guest = $invitation->guests->first();

        Livewire::test(RsvpForm::class, ['invitation' => $invitation])
            ->set('attending', 0)
            ->set('guests.0.attending', 0)
            ->call('submit')
            ->assertRedirect(route('rsvp.success'));

        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
            'status' => 'declined',
        ]);

        $this->assertDatabaseHas('guests', [
            'id' => $guest->id,
            'attending' => false,
        ]);
    }

    public function test_declining_forces_all_guests_to_not_attending_on_submit(): void
    {
        Queue::fake();
        $invitation = $this->makeInvitation(2);
        $guests = $invitation->guests;

        // Even if individual guests were marked as attending, declining the whole invitation
        // overrides them to not attending on submit.
        Livewire::test(RsvpForm::class, ['invitation' => $invitation])
            ->set('attending', 0)
            ->set('guests.0.attending', 1)
            ->set('guests.1.attending', 1)
            ->call('submit')
            ->assertRedirect(route('rsvp.success'));

        $this->assertDatabaseHas('guests', ['id' => $guests[0]->id, 'attending' => false]);
        $this->assertDatabaseHas('guests', ['id' => $guests[1]->id, 'attending' => false]);
    }

    public function test_guest_name_max_length_validation(): void
    {
        $invitation = $this->makeInvitation(1);

        Livewire::test(RsvpForm::class, ['invitation' => $invitation])
            ->set('guests.0.name', str_repeat('a', 256))
            ->call('submit')
            ->assertHasErrors(['guests.0.name']);
    }

    public function test_dietary_restrictions_max_length_validation(): void
    {
        $invitation = $this->makeInvitation(1);

        Livewire::test(RsvpForm::class, ['invitation' => $invitation])
            ->set('guests.0.dietary_restrictions', str_repeat('a', 501))
            ->call('submit')
            ->assertHasErrors(['guests.0.dietary_restrictions']);
    }

    public function test_honeypot_filled_silently_redirects(): void
    {
        Queue::fake();
        $invitation = $this->makeInvitation(1);

        Livewire::test(RsvpForm::class, ['invitation' => $invitation])
            ->set('website', 'bot-filled')
            ->call('submit')
            ->assertRedirect(route('rsvp.success'));

        // No changes should have been made
        $this->assertDatabaseHas('invitations', [
            'id' => $invitation->id,
            'status' => 'pending',
        ]);
    }

    public function test_guest_id_from_another_invitation_is_rejected(): void
    {
        $invitation = $this->makeInvitation(1);
        $other = $this->makeInvitation(1);

        Livewire::test(RsvpForm::class, ['invitation' => $invitation])
            ->set('guests.0.id', $other->guests->first()->id)
            ->call('submit')
            ->assertForbidden();
    }

    public function test_rate_limiting_blocks_after_five_attempts(): void
    {
        $invitation = $this->makeInvitation(1);

        $test = Livewire::test(RsvpForm::class, ['invitation' => $invitation]);

        for ($i = 0; $i < 5; $i++) {
            $test->call('submit');
        }

        $test->call('submit')
            ->assertHasErrors(['rate_limit']);
    }
}
