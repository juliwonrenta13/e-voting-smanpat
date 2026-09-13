@extends('layouts.admin')

@section('title', 'Pengaturan Pemilu - E-Voting SMAN 4')
@section('page-title', 'Pengaturan Pemilihan')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <div class="mb-6">
            <h1 class="text-lg font-bold text-slate-900">Konfigurasi Periode Pemilihan</h1>
            <p class="text-xs text-slate-500">Atur judul pemilihan, jadwal buka/tutup bilik suara digital, dan status operasional</p>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="sm:col-span-2">
                    <label for="title" class="block text-xs font-bold text-slate-700 mb-1.5">Nama / Judul Pemilihan</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $setting->title) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    @error('title') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="academic_year" class="block text-xs font-bold text-slate-700 mb-1.5">Tahun Ajaran</label>
                    <input type="text" name="academic_year" id="academic_year" value="{{ old('academic_year', $setting->academic_year) }}" required placeholder="Contoh: 2026/2027"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    @error('academic_year') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="status" class="block text-xs font-bold text-slate-700 mb-1.5">Status Pemilihan</label>
                <select name="status" id="status" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    <option value="open" {{ old('status', $setting->status) === 'open' ? 'selected' : '' }}>
                        🟢 OPEN (Bilik Suara Dibuka - Siswa dapat memilih)
                    </option>
                    <option value="scheduled" {{ old('status', $setting->status) === 'scheduled' ? 'selected' : '' }}>
                        🟡 SCHEDULED (Otomatis buka sesuai rentang tanggal & jam)
                    </option>
                    <option value="closed" {{ old('status', $setting->status) === 'closed' ? 'selected' : '' }}>
                        🔴 CLOSED (Pemilihan Ditutup / Selesai - Tidak menerima suara)
                    </option>
                    <option value="draft" {{ old('status', $setting->status) === 'draft' ? 'selected' : '' }}>
                        ⚪ DRAFT (Persiapan Data - Siswa tidak dapat login)
                    </option>
                </select>
                @error('status') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                <div>
                    <label for="start_at" class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal & Jam Mulai (Opsional)</label>
                    <input type="datetime-local" name="start_at" id="start_at" 
                        value="{{ old('start_at', $setting->start_at ? $setting->start_at->format('Y-m-d\TH:i') : '') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    @error('start_at') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="end_at" class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal & Jam Berakhir (Opsional)</label>
                    <input type="datetime-local" name="end_at" id="end_at" 
                        value="{{ old('end_at', $setting->end_at ? $setting->end_at->format('Y-m-d\TH:i') : '') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    @error('end_at') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-5 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition">
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
