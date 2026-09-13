<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\ElectionSetting;
use App\Models\Position;
use App\Models\Vote;
use App\Models\Voter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VotingProcessTest extends TestCase
{
    use RefreshDatabase;

    protected $positions = [];
    protected $candidates = [];

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Election Setting
        ElectionSetting::create([
            'title' => 'Pemilihan Pengurus OSIS & MPK SMAN 4',
            'academic_year' => '2026/2027',
            'status' => 'open',
            'start_at' => now()->startOfDay(),
            'end_at' => now()->addDays(7)->endOfDay(),
        ]);

        // 2. Create 6 positions (3 OSIS + 3 MPK)
        $positionsData = [
            ['section' => 'OSIS', 'name' => 'Ketua OSIS', 'order' => 1],
            ['section' => 'OSIS', 'name' => 'Sekretaris OSIS', 'order' => 2],
            ['section' => 'OSIS', 'name' => 'Bendahara OSIS', 'order' => 3],
            ['section' => 'MPK', 'name' => 'Ketua MPK', 'order' => 4],
            ['section' => 'MPK', 'name' => 'Sekretaris MPK', 'order' => 5],
            ['section' => 'MPK', 'name' => 'Bendahara MPK', 'order' => 6],
        ];

        foreach ($positionsData as $pos) {
            $p = Position::create($pos);
            $this->positions[] = $p;

            // 2 candidates per position
            for ($num = 1; $num <= 2; $num++) {
                $c = Candidate::create([
                    'position_id' => $p->id,
                    'candidate_number' => $num,
                    'name' => "Kandidat {$num} {$p->name}",
                    'status' => 'active',
                ]);
                $this->candidates[$p->id][$num] = $c;
            }
        }
    }

    public function test_voter_can_view_ballot_when_authenticated_with_unused_token(): void
    {
        $voter = Voter::create(['token' => 'VOTE1234', 'status' => 'unused']);

        $response = $this->withSession(['voter_id' => $voter->id, 'voter_token' => $voter->token])
            ->get(route('voting.index'));

        $response->assertStatus(200);
        $response->assertSee('SURAT SUARA DIGITAL RESMI');
        foreach ($this->positions as $p) {
            $response->assertSee($p->name);
        }
    }

    public function test_voter_can_submit_all_6_votes_in_one_atomic_transaction(): void
    {
        $voter = Voter::create(['token' => 'VOTEATOM1', 'status' => 'unused']);

        // Prepare 6 selections (choose candidate #1 for each position)
        $selections = [];
        foreach ($this->positions as $p) {
            $selections[$p->id] = $this->candidates[$p->id][1]->id;
        }

        $this->assertEquals(6, count($selections));

        $response = $this->withSession(['voter_id' => $voter->id, 'voter_token' => $voter->token])
            ->post(route('voting.submit'), [
                'selections' => $selections,
            ]);

        $response->assertRedirect(route('voting.success'));

        // Assert 6 votes stored in database
        $this->assertEquals(6, Vote::where('voter_id', $voter->id)->count());

        // Assert voter status updated to used
        $voter->refresh();
        $this->assertEquals('used', $voter->status);
        $this->assertNotNull($voter->voted_at);

        // Assert voter session was cleared to prevent replay
        $this->assertNull(session('voter_id'));
    }

    public function test_partial_submission_is_rejected_and_rolls_back_completely(): void
    {
        $voter = Voter::create(['token' => 'PARTIAL1', 'status' => 'unused']);

        // Only select 5 out of 6 positions
        $selections = [];
        for ($i = 0; $i < 5; $i++) {
            $p = $this->positions[$i];
            $selections[$p->id] = $this->candidates[$p->id][1]->id;
        }

        $this->assertEquals(5, count($selections));

        $response = $this->withSession(['voter_id' => $voter->id, 'voter_token' => $voter->token])
            ->post(route('voting.submit'), [
                'selections' => $selections,
            ]);

        // Should redirect back with error
        $response->assertSessionHas('error');

        // ZERO votes must be recorded in database
        $this->assertEquals(0, Vote::where('voter_id', $voter->id)->count());

        // Voter status must remain unused
        $voter->refresh();
        $this->assertEquals('unused', $voter->status);
    }

    public function test_invalid_candidate_for_position_is_rejected_and_rolled_back(): void
    {
        $voter = Voter::create(['token' => 'INVALIDCAND', 'status' => 'unused']);

        $selections = [];
        foreach ($this->positions as $p) {
            $selections[$p->id] = $this->candidates[$p->id][1]->id;
        }

        // Tamper: Assign candidate from position 2 to position 1
        $pos1 = $this->positions[0];
        $pos2 = $this->positions[1];
        $selections[$pos1->id] = $this->candidates[$pos2->id][1]->id;

        $response = $this->withSession(['voter_id' => $voter->id, 'voter_token' => $voter->token])
            ->post(route('voting.submit'), [
                'selections' => $selections,
            ]);

        $response->assertSessionHas('error');

        // Zero votes recorded
        $this->assertEquals(0, Vote::where('voter_id', $voter->id)->count());
        $voter->refresh();
        $this->assertEquals('unused', $voter->status);
    }

    public function test_used_token_cannot_vote_again(): void
    {
        $voter = Voter::create([
            'token' => 'ALREADYUSED',
            'status' => 'used',
            'voted_at' => now(),
        ]);

        $selections = [];
        foreach ($this->positions as $p) {
            $selections[$p->id] = $this->candidates[$p->id][1]->id;
        }

        $response = $this->withSession(['voter_id' => $voter->id, 'voter_token' => $voter->token])
            ->post(route('voting.submit'), [
                'selections' => $selections,
            ]);

        // Denied
        $this->assertEquals(0, Vote::where('voter_id', $voter->id)->count());
    }
}
