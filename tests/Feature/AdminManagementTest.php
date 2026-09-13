<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Candidate;
use App\Models\ElectionSetting;
use App\Models\Position;
use App\Models\User;
use App\Models\Voter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@smanpat.sch.id',
            'role' => 'admin',
        ]);

        ElectionSetting::create([
            'title' => 'Pemilihan Pengurus OSIS & MPK SMAN 4',
            'academic_year' => '2026/2027',
            'status' => 'open',
            'start_at' => now()->startOfDay(),
            'end_at' => now()->addDays(7)->endOfDay(),
        ]);
    }

    public function test_admin_can_create_position(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.positions.store'), [
            'section' => 'OSIS',
            'name' => 'Koordinator IT & Publikasi',
            'order' => 7,
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.positions.index'));
        $this->assertDatabaseHas('positions', ['name' => 'Koordinator IT & Publikasi']);
        $this->assertDatabaseHas('audit_logs', ['action' => 'POSITION_CREATE']);
    }

    public function test_admin_can_create_candidate_with_photo(): void
    {
        Storage::fake('public');

        $position = Position::create([
            'section' => 'OSIS',
            'name' => 'Ketua OSIS',
            'order' => 1,
            'status' => 'active',
        ]);

        $file = UploadedFile::fake()->image('kandidat.jpg', 400, 500);

        $response = $this->actingAs($this->admin)->post(route('admin.candidates.store'), [
            'position_id' => $position->id,
            'candidate_number' => 1,
            'name' => 'Ahmad Fathoni',
            'photo' => $file,
            'vision' => 'Visi uji coba',
            'mission' => 'Misi uji coba',
            'status' => 'active',
        ]);

        $response->assertRedirect(route('admin.candidates.index', ['position_id' => $position->id]));
        $this->assertDatabaseHas('candidates', ['name' => 'Ahmad Fathoni']);

        $candidate = Candidate::where('name', 'Ahmad Fathoni')->first();
        $this->assertNotNull($candidate->photo);
        Storage::disk('public')->assertExists($candidate->photo);
        $this->assertDatabaseHas('audit_logs', ['action' => 'CANDIDATE_CREATE']);
    }

    public function test_admin_can_generate_batch_tokens(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.tokens.generate-batch'), [
            'count' => 25,
            'length' => 8,
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals(25, Voter::count());
        $this->assertDatabaseHas('audit_logs', ['action' => 'TOKEN_GENERATE_BATCH']);
    }

    public function test_admin_can_export_tokens_csv(): void
    {
        Voter::create(['token' => 'TKN12345', 'status' => 'unused']);
        Voter::create(['token' => 'TKN67890', 'status' => 'used', 'voted_at' => now()]);

        $response = $this->actingAs($this->admin)->get(route('admin.tokens.export'));

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
        $this->assertDatabaseHas('audit_logs', ['action' => 'TOKEN_EXPORT']);
    }

    public function test_admin_can_update_election_settings(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.settings.update'), [
            'title' => 'Pemilihan Raya OSIS & MPK 2026',
            'academic_year' => '2026/2027',
            'status' => 'closed',
            'start_at' => now()->subDay()->format('Y-m-d\TH:i'),
            'end_at' => now()->format('Y-m-d\TH:i'),
        ]);

        $response->assertRedirect(route('admin.settings.edit'));
        $this->assertDatabaseHas('election_settings', ['title' => 'Pemilihan Raya OSIS & MPK 2026', 'status' => 'closed']);
        $this->assertDatabaseHas('audit_logs', ['action' => 'ELECTION_SETTINGS_UPDATE']);
    }

    public function test_admin_can_view_results_and_print_page(): void
    {
        $pos = Position::create(['section' => 'OSIS', 'name' => 'Ketua OSIS', 'order' => 1, 'status' => 'active']);
        Candidate::create(['position_id' => $pos->id, 'candidate_number' => 1, 'name' => 'Calon 1', 'status' => 'active']);

        $resIndex = $this->actingAs($this->admin)->get(route('admin.results.index'));
        $resIndex->assertStatus(200);
        $resIndex->assertSee('Rekapitulasi Hasil Pemilihan');

        $resPrint = $this->actingAs($this->admin)->get(route('admin.results.print'));
        $resPrint->assertStatus(200);
        $resPrint->assertSee('BERITA ACARA', false);
        $resPrint->assertSee('REKAPITULASI HASIL SUARA', false);
    }
}
