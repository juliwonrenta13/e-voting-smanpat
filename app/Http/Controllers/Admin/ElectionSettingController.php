<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ElectionSetting;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ElectionSettingController extends Controller
{
    public function edit()
    {
        $setting = ElectionSetting::current();
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = ElectionSetting::current();

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'academic_year' => ['required', 'string', 'max:50'],
            'status' => ['required', Rule::in(['draft', 'scheduled', 'open', 'closed'])],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
        ]);

        $setting->update($validated);

        AuditLogService::log(
            'ELECTION_SETTINGS_UPDATE',
            "Memperbarui pengaturan pemilu: Status {$setting->status}, Judul: {$setting->title}"
        );

        return redirect()->route('admin.settings.edit')->with('success', 'Pengaturan pemilihan berhasil diperbarui.');
    }
}
