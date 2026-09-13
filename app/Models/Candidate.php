<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_id',
        'candidate_number',
        'name',
        'photo',
        'vision',
        'mission',
        'status',
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    protected function photoUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->photo && Storage::disk('public')->exists($this->photo)) {
                    return Storage::url($this->photo);
                }
                return null;
            }
        );
    }
}
