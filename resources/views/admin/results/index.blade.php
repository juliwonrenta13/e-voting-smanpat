@extends('layouts.admin')

@section('title', 'Hasil Pemilihan Suara - E-Voting SMAN 4')
@section('page-title', 'Hasil Rekapitulasi Suara')

@section('content')
<div class="space-y-6">
    <!-- Header with Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Rekapitulasi Hasil Pemilihan</h1>
            <p class="text-xs text-slate-500 mt-1">Data hasil perolehan suara bersifat read-only dan diperbarui secara real-time</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.results.print') }}" target="_blank" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition flex items-center space-x-1.5 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak Lembar Rekap</span>
            </a>
        </div>
    </div>

    <!-- Stats Bar -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-bold uppercase">Total Hak Pilih</p>
                <h3 class="text-xl font-extrabold text-slate-900 mt-0.5">{{ number_format($totalVoters) }} Pemilih</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-bold uppercase">Suara Masuk</p>
                <h3 class="text-xl font-extrabold text-emerald-600 mt-0.5">{{ number_format($totalVoted) }} Pemilih</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-bold uppercase">Partisipasi</p>
                <h3 class="text-xl font-extrabold text-blue-600 mt-0.5">{{ $turnout }}%</h3>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex items-center space-x-2 border-b border-slate-200 pb-2">
        <a href="{{ route('admin.results.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ empty($section) ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            Semua Jabatan (6)
        </a>
        <a href="{{ route('admin.results.index', ['section' => 'OSIS']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $section === 'OSIS' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            OSIS (3 Jabatan)
        </a>
        <a href="{{ route('admin.results.index', ['section' => 'MPK']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $section === 'MPK' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
            MPK (3 Jabatan)
        </a>
    </div>

    <!-- Results Per Position -->
    <div class="space-y-6">
        @foreach($positions as $pos)
            @php
                $posTotalVotes = $pos->candidates->sum('votes_count');
                $maxVotes = $pos->candidates->max('votes_count');
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <!-- Position Header -->
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex items-center space-x-3">
                        <span class="px-2.5 py-1 text-xs font-extrabold rounded-lg {{ $pos->section === 'OSIS' ? 'bg-blue-100 text-blue-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $pos->section }}
                        </span>
                        <h2 class="text-base font-extrabold text-slate-900">{{ $pos->name }}</h2>
                    </div>
                    <span class="text-xs font-bold text-slate-500">Total Suara Masuk: <span class="text-slate-900 font-extrabold">{{ $posTotalVotes }}</span></span>
                </div>

                <!-- Candidates List & Bars -->
                <div class="p-6 divide-y divide-slate-100 space-y-4">
                    @forelse($pos->candidates as $rank => $c)
                        @php
                            $cPercent = $posTotalVotes > 0 ? round(($c->votes_count / $posTotalVotes) * 100, 1) : 0;
                            $isLeader = ($maxVotes > 0 && $c->votes_count === $maxVotes);
                        @endphp
                        <div class="pt-4 first:pt-0">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-800 font-black text-xs flex items-center justify-center border border-slate-200">
                                        {{ $c->candidate_number }}
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2">
                                            <h3 class="text-sm font-bold text-slate-900">{{ $c->name }}</h3>
                                            @if($isLeader)
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-200 flex items-center space-x-1">
                                                    <span>👑</span>
                                                    <span>Unggul</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <span class="text-sm font-black text-slate-900">{{ number_format($c->votes_count) }} Suara</span>
                                    <span class="text-xs text-slate-400 font-medium ml-1">({{ $cPercent }}%)</span>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="w-full bg-slate-100 rounded-full h-3.5 overflow-hidden p-0.5 border border-slate-200">
                                <div class="h-full rounded-full transition-all duration-700 {{ $isLeader ? 'bg-gradient-to-r from-amber-500 to-indigo-600' : 'bg-slate-400' }}"
                                    style="width: {{ min(100, $cPercent) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-4">Belum ada kandidat untuk jabatan ini.</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
