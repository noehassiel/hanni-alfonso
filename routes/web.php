<?php

use App\Http\Controllers\Public\InvitationController;
use App\Http\Controllers\Public\RsvpController;
use Illuminate\Support\Facades\Route;

Route::get('/', [InvitationController::class, 'landing'])->name('home');
Route::get('/i/{token}', [InvitationController::class, 'show'])->name('invitation.show');
Route::get('/i/{token}/verify', [InvitationController::class, 'verify'])->name('invitation.verify');
Route::get('/i/{token}/calendario.ics', [InvitationController::class, 'calendarDownload'])->name('invitation.calendar');
Route::get('/rsvp/success', [RsvpController::class, 'success'])->name('rsvp.success');
