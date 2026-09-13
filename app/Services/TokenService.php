<?php

namespace App\Services;

use App\Models\Voter;
use Illuminate\Support\Str;

class TokenService
{
    /**
     * Characters used for secure random token generation (excluding ambiguous characters like 0, O, 1, I, L).
     */
    private const CHARSET = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';

    /**
     * Generate a single unique random token.
     */
    public function generateToken(int $length = 8): string
    {
        do {
            $token = '';
            $maxIndex = strlen(self::CHARSET) - 1;
            for ($i = 0; $i < $length; $i++) {
                $token .= self::CHARSET[random_int(0, $maxIndex)];
            }
        } while (Voter::where('token', $token)->exists());

        return $token;
    }

    /**
     * Generate a batch of unique tokens.
     */
    public function generateBatch(int $count = 50, int $length = 8): int
    {
        $created = 0;
        $batch = [];
        $existingTokens = Voter::pluck('token')->flip()->toArray();

        for ($i = 0; $i < $count; $i++) {
            do {
                $token = '';
                $maxIndex = strlen(self::CHARSET) - 1;
                for ($j = 0; $j < $length; $j++) {
                    $token .= self::CHARSET[random_int(0, $maxIndex)];
                }
            } while (isset($existingTokens[$token]));

            $existingTokens[$token] = true;
            $now = now();
            $batch[] = [
                'token' => $token,
                'status' => 'unused',
                'voted_at' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];

            // Insert in chunks of 200
            if (count($batch) >= 200) {
                Voter::insert($batch);
                $created += count($batch);
                $batch = [];
            }
        }

        if (!empty($batch)) {
            Voter::insert($batch);
            $created += count($batch);
        }

        return $created;
    }
}
