@extends('layouts.admin')

@section('title', 'Tambah Jabatan - E-Voting SMAN 4')
@section('page-title', 'Tambah Jabatan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <div class="mb-6">
            <h1 class="text-lg font-bold text-slate-900">Form Tambah Jabatan</h1>
            <p class="text-xs text-slate-500">Buat jabatan baru untuk perwakilan OSIS atau MPK</p>
        </div>

        <form action="{{ route('admin.positions.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="section" class="block text-xs font-bold text-slate-700 mb-1.5">Bagian Organisasi</label>
                <select name="section" id="section" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    <option value="OSIS" {{ old('section') === 'OSIS' ? 'selected' : '' }}>OSIS (Organisasi Siswa Intra Sekolah)</option>
                    <option value="MPK" {{ old('section') === 'MPK' ? 'selected' : '' }}>MPK (Majelis Perwakilan Kelas)</option>
                </select>
                @error('section') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Jabatan</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Ketua OSIS"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="order" class="block text-xs font-bold text-slate-700 mb-1.5">Urutan Tampil di Surat Suara</label>
                <input type="number" name="order" id="order" value="{{ old('order', 1) }}" min="0" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                @error('order') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="status" class="block text-xs font-bold text-slate-700 mb-1.5">Status Jabatan</label>
                <select name="status" id="status" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif (Tampil di bilik suara)</option>
                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif (Sembunyikan)</option>
                </select>
                @error('status') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.positions.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition">
                    Simpan Jabatan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
