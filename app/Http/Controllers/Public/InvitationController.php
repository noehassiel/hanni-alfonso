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

        $invitation->markAsAccessed();

        return view('public.invitation', compact('invitation'));
    }
}
