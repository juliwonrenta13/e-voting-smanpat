<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'academic_year',
        'status',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function isVotingOpen(): bool
    {
        if ($this->status === 'closed' || $this->status === 'draft') {
            return false;
        }

        $now = now();
        if ($this->status === 'scheduled') {
            if ($this->start_at && $now->lt($this->start_at)) {
                return false;
            }
            if ($this->end_at && $now->gt($this->end_at)) {
                return false;
            }
            return true;
        }

        if ($this->status === 'open') {
            if ($this->start_at && $now->lt($this->start_at)) {
                return false;
            }
            if ($this->end_at && $now->gt($this->end_at)) {
                return false;
            }
            return true;
        }

        return false;
    }

    public static function current(): self
    {
        return static::firstOrCreate([], [
            'title' => 'Pemilihan Pengurus OSIS & MPK SMAN 4',
            'academic_year' => '2026/2027',
            'status' => 'open',
            'start_at' => now()->startOfDay(),
            'end_at' => now()->addDays(7)->endOfDay(),
        ]);
    }
}
