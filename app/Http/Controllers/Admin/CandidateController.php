<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\Position;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CandidateController extends Controller
{
    public function index(Request $request)
    {
        $positionId = $request->get('position_id');

        $query = Candidate::with('position')->orderBy('position_id')->orderBy('candidate_number');
        if ($positionId) {
            $query->where('position_id', $positionId);
        }

        $candidates = $query->get();
        $positions = Position::ordered()->get();

        return view('admin.candidates.index', compact('candidates', 'positions', 'positionId'));
    }

    public function create()
    {
        $positions = Position::ordered()->get();
        return view('admin.candidates.create', compact('positions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'position_id' => ['required', 'exists:positions,id'],
            'candidate_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('candidates')->where(function ($query) use ($request) {
                    return $query->where('position_id', $request->position_id);
                }),
            ],
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'vision' => ['nullable', 'string'],
            'mission' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('candidates', 'public');
            $validated['photo'] = $path;
        }

        $candidate = Candidate::create($validated);
        AuditLogService::log('CANDIDATE_CREATE', "Menambahkan kandidat: {$candidate->name} (No. {$candidate->candidate_number} - {$candidate->position->name})");

        return redirect()->route('admin.candidates.index', ['position_id' => $candidate->position_id])
            ->with('success', 'Kandidat berhasil ditambahkan.');
    }

    public function edit(Candidate $candidate)
    {
        $positions = Position::ordered()->get();
        return view('admin.candidates.edit', compact('candidate', 'positions'));
    }

    public function update(Request $request, Candidate $candidate)
    {
        $validated = $request->validate([
            'position_id' => ['required', 'exists:positions,id'],
            'candidate_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('candidates')
                    ->where(function ($query) use ($request) {
                        return $query->where('position_id', $request->position_id);
                    })
                    ->ignore($candidate->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'vision' => ['nullable', 'string'],
            'mission' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($candidate->photo && Storage::disk('public')->exists($candidate->photo)) {
                Storage::disk('public')->delete($candidate->photo);
            }
            $path = $request->file('photo')->store('candidates', 'public');
            $validated['photo'] = $path;
        }

        $candidate->update($validated);
        AuditLogService::log('CANDIDATE_UPDATE', "Memperbarui data kandidat: {$candidate->name} (ID: {$candidate->id})");

        return redirect()->route('admin.candidates.index', ['position_id' => $candidate->position_id])
            ->with('success', 'Data kandidat berhasil diperbarui.');
    }

    public function toggleStatus(Candidate $candidate)
    {
        $newStatus = $candidate->status === 'active' ? 'inactive' : 'active';
        $candidate->update(['status' => $newStatus]);

        AuditLogService::log('CANDIDATE_TOGGLE', "Mengubah status kandidat {$candidate->name} menjadi {$newStatus}");

        return back()->with('success', "Status kandidat {$candidate->name} diubah menjadi {$newStatus}.");
    }

    public function destroy(Candidate $candidate)
    {
        if ($candidate->votes()->exists()) {
            return back()->with('error', "Kandidat '{$candidate->name}' tidak dapat dihapus karena sudah memperoleh suara pemilih.");
        }

        if ($candidate->photo && Storage::disk('public')->exists($candidate->photo)) {
            Storage::disk('public')->delete($candidate->photo);
        }

        $name = $candidate->name;
        $posId = $candidate->position_id;
        $candidate->delete();

        AuditLogService::log('CANDIDATE_DELETE', "Menghapus kandidat: {$name}");

        return redirect()->route('admin.candidates.index', ['position_id' => $posId])
            ->with('success', "Kandidat {$name} berhasil dihapus.");
    }
}
