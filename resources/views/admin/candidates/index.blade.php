@extends('layouts.admin')

@section('title', 'Manajemen Kandidat - E-Voting SMAN 4')
@section('page-title', 'Manajemen Kandidat')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Daftar Kandidat Calon</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola kandidat yang berkompetisi pada setiap jabatan OSIS dan MPK</p>
        </div>
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('admin.candidates.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Kandidat</span>
            </a>
        </div>
    </div>

    <!-- Filter by Position Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-wrap items-center justify-between gap-3">
        <form action="{{ route('admin.candidates.index') }}" method="GET" class="flex items-center space-x-3 w-full sm:w-auto">
            <label for="position_id" class="text-xs font-bold text-slate-600 whitespace-nowrap">Filter Jabatan:</label>
            <select name="position_id" id="position_id" onchange="this.form.submit()" class="px-3 py-1.5 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                <option value="">-- Semua Jabatan ({{ $positions->count() }}) --</option>
                @foreach($positions as $p)
                    <option value="{{ $p->id }}" {{ (string)$positionId === (string)$p->id ? 'selected' : '' }}>
                        [{{ $p->section }}] {{ $p->name }}
                    </option>
                @endforeach
            </select>
        </form>

        <span class="text-xs text-slate-400">Menampilkan {{ $candidates->count() }} kandidat</span>
    </div>

    <!-- Candidates Grid / Table -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($candidates as $c)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-md transition flex flex-col overflow-hidden">
                <div class="p-5 flex-1">
                    <div class="flex items-start space-x-4">
                        <!-- Photo or Placeholder -->
                        <div class="w-20 h-24 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0 flex items-center justify-center relative shadow-inner">
                            @if($c->photo_url)
                                <img src="{{ $c->photo_url }}" alt="{{ $c->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex flex-col items-center justify-center text-slate-300 p-2 text-center">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-[9px] mt-1 font-semibold">Foto Kosong</span>
                                </div>
                            @endif
                            <span class="absolute top-1 left-1 w-6 h-6 rounded-lg bg-indigo-600 text-white font-black text-xs flex items-center justify-center shadow">
                                {{ $c->candidate_number }}
                            </span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-1.5 mb-1">
                                <span class="px-2 py-0.5 text-[10px] font-extrabold rounded-md {{ $c->position->section === 'OSIS' ? 'bg-blue-50 text-blue-700' : 'bg-emerald-50 text-emerald-700' }}">
                                    {{ $c->position->section }}
                                </span>
                                <span class="text-[11px] font-medium text-slate-500 truncate">{{ $c->position->name }}</span>
                            </div>
                            <h3 class="font-bold text-slate-900 text-sm leading-snug">{{ $c->name }}</h3>
                            <p class="text-[11px] text-slate-400 mt-0.5 font-medium">Nomor Urut {{ $c->candidate_number }}</p>
                            
                            <div class="mt-2.5 flex items-center space-x-2">
                                <form action="{{ route('admin.candidates.toggle', $c) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold px-2 py-0.5 rounded-full transition {{ $c->status === 'active' ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                        {{ $c->status === 'active' ? '● Aktif' : '○ Nonaktif' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Vision & Mission Preview -->
                    @if($c->vision)
                        <div class="mt-4 pt-3 border-t border-slate-100 text-xs">
                            <p class="font-bold text-slate-700 text-[11px] mb-0.5">Visi Singkat:</p>
                            <p class="text-slate-500 line-clamp-2 italic">"{{ $c->vision }}"</p>
                        </div>
                    @endif
                </div>

                <!-- Footer Action Buttons -->
                <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-[11px] text-slate-400">ID: #{{ $c->id }}</span>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.candidates.edit', $c) }}" class="px-2.5 py-1 rounded-lg text-indigo-600 hover:bg-indigo-50 font-semibold transition">
                            Edit
                        </a>

                        @if($c->votes()->count() === 0)
                            <form action="{{ route('admin.candidates.destroy', $c) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kandidat {{ $c->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 rounded-lg text-rose-600 hover:bg-rose-50 font-semibold transition">
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-slate-200/80">
                <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <p class="text-sm font-semibold text-slate-700">Belum ada data kandidat</p>
                <p class="text-xs text-slate-400 mt-1">Silakan tambahkan kandidat untuk jabatan ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
