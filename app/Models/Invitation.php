<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Invitation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'group_name',
        'access_code',
        'magic_link_token',
        'email',
        'phone',
        'max_guests',
        'personal_message',
        'status',
        'confirmed_at',
        'invitation_sent_at',
        'first_reminder_sent_at',
        'second_reminder_sent_at',
        'last_accessed_at',
        'access_count',
    ];

    protected function casts(): array
    {
        return [
            'confirmed_at' => 'datetime',
            'invitation_sent_at' => 'datetime',
            'first_reminder_sent_at' => 'datetime',
            'second_reminder_sent_at' => 'datetime',
            'last_accessed_at' => 'datetime',
        ];
    }

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class)->orderBy('position');
    }

    public function primaryGuest(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Guest::class)->where('is_primary', true);
    }

    public function notificationLogs(): HasMany
    {
        return $this->hasMany(NotificationLog::class);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeDeclined($query)
    {
        return $query->where('status', 'declined');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeNeedsFirstReminder($query)
    {
        return $query->whereNotNull('confirmed_at')
            ->whereNull('first_reminder_sent_at')
            ->whereDate('confirmed_at', '<=', now()->subMonths(2));
    }

    public function scopeNeedsSecondReminder($query)
    {
        return $query->whereNotNull('confirmed_at')
            ->whereNotNull('first_reminder_sent_at')
            ->whereNull('second_reminder_sent_at')
            ->whereDate('first_reminder_sent_at', '<=', now()->subMonths(2));
    }

    public function getConfirmedGuestsCountAttribute(): int
    {
        return $this->guests()->where('attending', true)->count();
    }

    public function getMagicLinkAttribute(): string
    {
        if (!$this->magic_link_token) {
            $this->generateMagicLinkToken();
        }

        return route('invitation.show', ['token' => $this->magic_link_token]);
    }

    public function generateAccessCode(): void
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('access_code', $code)->exists());

        $this->access_code = $code;
        $this->save();
    }

    public function generateMagicLinkToken(): void
    {
        do {
            $token = Str::random(64);
        } while (self::where('magic_link_token', $token)->exists());

        $this->magic_link_token = $token;
        $this->save();
    }

    public function markAsAccessed(): void
    {
        $this->increment('access_count');
        $this->update(['last_accessed_at' => now()]);
    }

    public function confirm(): void
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    public function decline(): void
    {
        $this->update([
            'status' => 'declined',
            'confirmed_at' => now(),
        ]);
    }
}
