<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PositionController extends Controller
{
    public function index()
    {
        $positions = Position::ordered()->withCount(['candidates', 'votes'])->get();
        return view('admin.positions.index', compact('positions'));
    }

    public function create()
    {
        return view('admin.positions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'section' => ['required', Rule::in(['OSIS', 'MPK'])],
            'name' => ['required', 'string', 'max:255', 'unique:positions,name'],
            'order' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $position = Position::create($validated);
        AuditLogService::log('POSITION_CREATE', "Menambahkan jabatan baru: {$position->name} ({$position->section})");

        return redirect()->route('admin.positions.index')->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function edit(Position $position)
    {
        return view('admin.positions.edit', compact('position'));
    }

    public function update(Request $request, Position $position)
    {
        $validated = $request->validate([
            'section' => ['required', Rule::in(['OSIS', 'MPK'])],
            'name' => ['required', 'string', 'max:255', Rule::unique('positions', 'name')->ignore($position->id)],
            'order' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $position->update($validated);
        AuditLogService::log('POSITION_UPDATE', "Memperbarui jabatan: {$position->name}");

        return redirect()->route('admin.positions.index')->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function toggleStatus(Position $position)
    {
        $newStatus = $position->status === 'active' ? 'inactive' : 'active';
        $position->update(['status' => $newStatus]);

        AuditLogService::log('POSITION_TOGGLE', "Mengubah status jabatan {$position->name} menjadi {$newStatus}");

        return back()->with('success', "Status jabatan {$position->name} berhasil diubah menjadi {$newStatus}.");
    }

    public function destroy(Position $position)
    {
        if ($position->votes()->exists()) {
            return back()->with('error', "Jabatan '{$position->name}' tidak dapat dihapus karena sudah memiliki data suara masuk.");
        }

        $name = $position->name;
        $position->delete();

        AuditLogService::log('POSITION_DELETE', "Menghapus jabatan: {$name}");

        return redirect()->route('admin.positions.index')->with('success', "Jabatan {$name} berhasil dihapus.");
    }
}
