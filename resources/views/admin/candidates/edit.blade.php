@extends('layouts.admin')

@section('title', 'Edit Kandidat - E-Voting SMAN 4')
@section('page-title', 'Edit Data Kandidat')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
        <div class="mb-6">
            <h1 class="text-lg font-bold text-slate-900">Edit Kandidat: {{ $candidate->name }}</h1>
            <p class="text-xs text-slate-500">Perbarui profil atau foto resmi kandidat</p>
        </div>

        <form action="{{ route('admin.candidates.update', $candidate) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="position_id" class="block text-xs font-bold text-slate-700 mb-1.5">Jabatan Yang Dituju</label>
                    <select name="position_id" id="position_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                        @foreach($positions as $p)
                            <option value="{{ $p->id }}" {{ old('position_id', $candidate->position_id) == $p->id ? 'selected' : '' }}>
                                [{{ $p->section }}] {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('position_id') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="candidate_number" class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Urut</label>
                    <input type="number" name="candidate_number" id="candidate_number" value="{{ old('candidate_number', $candidate->candidate_number) }}" min="1" max="99" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    @error('candidate_number') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap Siswa</label>
                <input type="text" name="name" id="name" value="{{ old('name', $candidate->name) }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <!-- Photo Upload with Preview -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Foto Resmi Kandidat (JPG, PNG, WEBP - Max 2MB)</label>
                <div class="flex items-start space-x-5">
                    <div id="previewContainer" class="w-24 h-32 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center flex-shrink-0 relative shadow-inner">
                        <img id="photoPreview" src="{{ $candidate->photo_url ?? '' }}" alt="Preview" class="w-full h-full object-cover {{ $candidate->photo_url ? '' : 'hidden' }}">
                        <span id="previewPlaceholder" class="text-[10px] text-slate-400 text-center px-2 {{ $candidate->photo_url ? 'hidden' : '' }}">Belum ada foto</span>
                    </div>

                    <div class="flex-1">
                        <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/jpg,image/webp"
                            class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                        <p class="text-[11px] text-slate-400 mt-1.5">Kosongkan jika tidak ingin mengganti foto kandidat saat ini.</p>
                        @error('photo') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div>
                <label for="vision" class="block text-xs font-bold text-slate-700 mb-1.5">Visi Kandidat</label>
                <textarea name="vision" id="vision" rows="3"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">{{ old('vision', $candidate->vision) }}</textarea>
                @error('vision') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="mission" class="block text-xs font-bold text-slate-700 mb-1.5">Misi Kandidat</label>
                <textarea name="mission" id="mission" rows="4"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">{{ old('mission', $candidate->mission) }}</textarea>
                @error('mission') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="status" class="block text-xs font-bold text-slate-700 mb-1.5">Status Keaktifan</label>
                <select name="status" id="status" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    <option value="active" {{ old('status', $candidate->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ old('status', $candidate->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.candidates.index') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');
    const previewPlaceholder = document.getElementById('previewPlaceholder');

    photoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.src = e.target.result;
                photoPreview.classList.remove('hidden');
                previewPlaceholder.classList.add('hidden');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
