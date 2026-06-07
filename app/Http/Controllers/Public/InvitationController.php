<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    public function landing()
    {
        return view('public.landing');
    }

    public function show(string $token)
    {
        $invitation = Invitation::where('magic_link_token', $token)
            ->with('guests')
            ->firstOrFail();

        // If the invitation has no email yet and this session hasn't verified OTP, gate them.
        if (! $invitation->email && ! session()->get('otp_verified_' . $invitation->id)) {
            return redirect()->route('invitation.verify', $token);
        }

        $invitation->markAsAccessed();

        return view('public.invitation', compact('invitation'));
    }

    public function verify(string $token)
    {
        $invitation = Invitation::where('magic_link_token', $token)->firstOrFail();

        // Already has email or session verified — go straight to invitation.
        if ($invitation->email || session()->get('otp_verified_' . $invitation->id)) {
            return redirect()->route('invitation.show', $token);
        }

        return view('public.otp-verify', compact('invitation'));
    }

    public function calendarDownload(string $token)
    {
        Invitation::where('magic_link_token', $token)->firstOrFail();

        $ics = implode("\r\n", [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Hanni & Alfonso//Wedding//ES',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'BEGIN:VEVENT',
            'UID:wedding-hanni-alfonso-20261024@hanni-alfonso',
            'DTSTART;TZID=America/Mexico_City:20261024T163000',
            'DTEND;TZID=America/Mexico_City:20261024T220000',
            'SUMMARY:Boda Hannia & Alfonso',
            'LOCATION:Carr. Querétaro-Tequisquiapan 707\, Purísima de Cubos\, 76295 Querétaro\, Qro.',
            'DESCRIPTION:¡Los esperamos en nuestra boda!\nMapa: https://maps.app.goo.gl/x1WDacDi6BWHyx9H6',
            'URL:https://maps.app.goo.gl/x1WDacDi6BWHyx9H6',
            'END:VEVENT',
            'END:VCALENDAR',
            '',
        ]);

        return response($ics, 200, [
            'Content-Type'        => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="boda-hanni-alfonso.ics"',
        ]);
    }
}
