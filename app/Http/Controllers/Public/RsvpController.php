<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Jobs\SendConfirmationToAdmins;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RsvpController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invitation_id' => 'required|exists:invitations,id',
            'attending' => 'required|boolean',
            'guests' => 'required|array',
            'guests.*.id' => 'required|exists:guests,id',
            'guests.*.name' => 'nullable|string|max:255',
            'guests.*.attending' => 'required|boolean',
            'guests.*.dietary_restrictions' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($validated) {
            $invitation = Invitation::findOrFail($validated['invitation_id']);

            if ($validated['attending']) {
                $invitation->confirm();
            } else {
                $invitation->decline();
            }

            foreach ($validated['guests'] as $guestData) {
                $guest = $invitation->guests()->findOrFail($guestData['id']);
                $guest->update([
                    'name' => $guestData['name'],
                    'attending' => $guestData['attending'],
                    'dietary_restrictions' => $guestData['dietary_restrictions'] ?? null,
                ]);
            }

            SendConfirmationToAdmins::dispatch($invitation);
        });

        return redirect()->route('rsvp.success');
    }

    public function success()
    {
        return view('public.success');
    }
}
