<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'section',
        'name',
        'order',
        'status',
    ];

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class)->orderBy('candidate_number');
    }

    public function activeCandidates(): HasMany
    {
        return $this->hasMany(Candidate::class)->where('status', 'active')->orderBy('candidate_number');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
