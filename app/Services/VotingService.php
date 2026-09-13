<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\ElectionSetting;
use App\Models\Position;
use App\Models\Vote;
use App\Models\Voter;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VotingService
{
    /**
     * Validate ballot selections before processing.
     *
     * @param array $selections Array of [position_id => candidate_id]
     * @throws ValidationException
     */
    public function validateBallot(array $selections): array
    {
        $setting = ElectionSetting::current();
        if (!$setting->isVotingOpen()) {
            throw ValidationException::withMessages([
                'election' => ['Periode pemilihan sedang tidak dibuka atau telah berakhir.'],
            ]);
        }

        // Get all active positions ordered
        $activePositions = Position::active()->ordered()->get();
        if ($activePositions->isEmpty()) {
            throw ValidationException::withMessages([
                'positions' => ['Tidak ada jabatan aktif yang tersedia untuk dipilih.'],
            ]);
        }

        // Must have selection for every active position
        if (count($selections) !== $activePositions->count()) {
            throw ValidationException::withMessages([
                'ballot' => ['Anda harus memilih tepat ' . $activePositions->count() . ' kandidat untuk seluruh jabatan yang tersedia.'],
            ]);
        }

        $validatedVotes = [];

        foreach ($activePositions as $position) {
            if (!isset($selections[$position->id])) {
                throw ValidationException::withMessages([
                    'ballot' => ['Pilihan untuk jabatan ' . $position->name . ' belum ditentukan.'],
                ]);
            }

            $candidateId = (int) $selections[$position->id];
            $candidate = Candidate::where('id', $candidateId)
                ->where('position_id', $position->id)
                ->where('status', 'active')
                ->first();

            if (!$candidate) {
                throw ValidationException::withMessages([
                    'ballot' => ['Kandidat yang dipilih untuk jabatan ' . $position->name . ' tidak valid atau tidak aktif.'],
                ]);
            }

            $validatedVotes[] = [
                'position_id' => $position->id,
                'candidate_id' => $candidate->id,
            ];
        }

        return $validatedVotes;
    }

    /**
     * Submit ballot atomically.
     *
     * @param int $voterId
     * @param array $selections [position_id => candidate_id]
     * @return array Receipt info
     * @throws Exception
     */
    public function submitBallot(int $voterId, array $selections): array
    {
        $validatedVotes = $this->validateBallot($selections);

        return DB::transaction(function () use ($voterId, $validatedVotes) {
            // Lock voter record to prevent concurrent/double submission
            $voter = Voter::where('id', $voterId)->lockForUpdate()->first();

            if (!$voter) {
                throw new Exception('Data pemilih tidak ditemukan.');
            }

            if ($voter->status !== 'unused') {
                throw new Exception('Token pemilih ini telah digunakan atau tidak aktif.');
            }

            // Double check existing votes for this voter
            if (Vote::where('voter_id', $voter->id)->exists()) {
                throw new Exception('Suara untuk pemilih ini sudah pernah tercatat sebelumnya.');
            }

            $now = now();
            $voteRecords = [];
            foreach ($validatedVotes as $vote) {
                $voteRecords[] = [
                    'voter_id' => $voter->id,
                    'position_id' => $vote['position_id'],
                    'candidate_id' => $vote['candidate_id'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            // Insert all votes
            Vote::insert($voteRecords);

            // Mark voter as used
            $voter->status = 'used';
            $voter->voted_at = $now;
            $voter->save();

            // Generate receipt hash for voter confirmation (without revealing choices)
            $receiptCode = strtoupper(substr(hash('sha256', $voter->id . $now->timestamp . config('app.key')), 0, 10));

            return [
                'voter_id' => $voter->id,
                'voted_at' => $now,
                'receipt_code' => $receiptCode,
                'vote_count' => count($voteRecords),
            ];
        });
    }
}
