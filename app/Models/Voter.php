<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voter extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'status',
        'voted_at',
    ];

    protected $casts = [
        'voted_at' => 'datetime',
    ];

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function canVote(): bool
    {
        return $this->status === 'unused';
    }

    public function scopeUnused($query)
    {
        return $query->where('status', 'unused');
    }

    public function scopeUsed($query)
    {
        return $query->where('status', 'used');
    }

    public function scopeDisabled($query)
    {
        return $query->where('status', 'disabled');
    }
}
