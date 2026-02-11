<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'invitation_id',
        'name',
        'is_primary',
        'attending',
        'dietary_restrictions',
        'notes',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'attending' => 'boolean',
        ];
    }

    public function invitation(): BelongsTo
    {
        return $this->belongsTo(Invitation::class);
    }

    public function scopeAttending($query)
    {
        return $query->where('attending', true);
    }

    public function scopeNotAttending($query)
    {
        return $query->where('attending', false);
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
