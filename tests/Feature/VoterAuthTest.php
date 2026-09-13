<?php

namespace Tests\Feature;

use App\Models\ElectionSetting;
use App\Models\Voter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoterAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        ElectionSetting::create([
            'title' => 'Pemilihan Pengurus OSIS & MPK SMAN 4',
            'academic_year' => '2026/2027',
            'status' => 'open',
            'start_at' => now()->startOfDay(),
            'end_at' => now()->addDays(7)->endOfDay(),
        ]);
    }

    public function test_voter_login_page_can_be_rendered(): void
    {
        $response = $this->get(route('voter.login'));
        $response->assertStatus(200);
        $response->assertSee('KODE TOKEN PEMILIH');
    }

    public function test_voter_can_login_with_valid_unused_token(): void
    {
        $voter = Voter::create([
            'token' => 'VALID123',
            'status' => 'unused',
        ]);

        $response = $this->post(route('voter.login.submit'), [
            'token' => 'VALID123',
        ]);

        $response->assertRedirect(route('voting.index'));
        $response->assertSessionHas('voter_id', $voter->id);
    }

    public function test_voter_cannot_login_with_invalid_token(): void
    {
        $response = $this->post(route('voter.login.submit'), [
            'token' => 'NONEXISTENT',
        ]);

        $response->assertSessionHasErrors('token');
        $this->assertNull(session('voter_id'));
    }

    public function test_voter_cannot_login_with_already_used_token(): void
    {
        Voter::create([
            'token' => 'USEDTOKEN',
            'status' => 'used',
            'voted_at' => now(),
        ]);

        $response = $this->post(route('voter.login.submit'), [
            'token' => 'USEDTOKEN',
        ]);

        $response->assertSessionHasErrors('token');
        $this->assertNull(session('voter_id'));
    }

    public function test_voter_cannot_login_with_disabled_token(): void
    {
        Voter::create([
            'token' => 'DISABLEDTKN',
            'status' => 'disabled',
        ]);

        $response = $this->post(route('voter.login.submit'), [
            'token' => 'DISABLEDTKN',
        ]);

        $response->assertSessionHasErrors('token');
        $this->assertNull(session('voter_id'));
    }

    public function test_voter_cannot_login_when_election_is_closed(): void
    {
        $setting = ElectionSetting::first();
        $setting->update(['status' => 'closed']);

        Voter::create([
            'token' => 'VALID123',
            'status' => 'unused',
        ]);

        $response = $this->post(route('voter.login.submit'), [
            'token' => 'VALID123',
        ]);

        $response->assertSessionHasErrors('token');
        $this->assertNull(session('voter_id'));
    }
}
