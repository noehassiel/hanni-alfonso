<?php

use App\Http\Controllers\Public\InvitationController;
use App\Http\Controllers\Public\RsvpController;
use Illuminate\Support\Facades\Route;

Route::get('/', [InvitationController::class, 'landing'])->name('home');
Route::get('/i/{token}', [InvitationController::class, 'show'])->name('invitation.show');
Route::post('/rsvp', [RsvpController::class, 'store'])->name('rsvp.store');
Route::get('/rsvp/success', [RsvpController::class, 'success'])->name('rsvp.success');
