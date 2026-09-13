@extends('layouts.admin')

@section('title', 'Dashboard Administrator - E-Voting SMAN 4')
@section('page-title', 'Dashboard Ringkasan')

@section('content')
<div class="space-y-6">

    <!-- Election Status Banner -->
    <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2.5 mb-2">
                    @if($setting->status === 'open')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                            PEMILIHAN AKTIF (BUKA)
                        </span>
                    @elseif($setting->status === 'closed')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30">
                            <span class="w-2 h-2 rounded-full bg-rose-400 mr-1.5"></span>
                            PEMILIHAN SELESAI (TUTUP)
                        </span>
                    @elseif($setting->status === 'scheduled')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                            <span class="w-2 h-2 rounded-full bg-amber-400 mr-1.5"></span>
                            TERJADWAL
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-500/20 text-slate-300 border border-slate-500/30">
                            DRAFT
                        </span>
                    @endif
                    <span class="text-xs text-indigo-200">Tahun Ajaran {{ $setting->academic_year }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">{{ $setting->title }}</h1>
                <p class="text-xs sm:text-sm text-indigo-200 mt-1">
                    Periode: {{ $setting->start_at ? $setting->start_at->translatedFormat('d M Y H:i') : 'Fleksibel' }} s/d {{ $setting->end_at ? $setting->end_at->translatedFormat('d M Y H:i') : 'Selesai' }}
                </p>
            </div>

            <div class="flex items-center space-x-2.5">
                <a href="{{ route('admin.results.index') }}" class="px-4 py-2 rounded-xl bg-white text-indigo-900 font-bold text-xs sm:text-sm hover:bg-indigo-50 shadow-md transition flex items-center space-x-1.5">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Lihat Hasil Live</span>
                </a>
                <a href="{{ route('admin.settings.edit') }}" class="px-3 py-2 rounded-xl bg-indigo-700/60 hover:bg-indigo-700 text-white font-medium text-xs sm:text-sm border border-indigo-500/30 transition">
                    Ubah Status
                </a>
            </div>
        </div>
    </div>

    <!-- Primary Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Voters -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Pemilih (Token)</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ number_format($totalVoters) }}</h3>
                <p class="text-xs text-slate-500 mt-1">Siswa terdaftar dalam sistem</p>
            </div>
        </div>

        <!-- Voted Count -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Suara Masuk</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl sm:text-3xl font-extrabold text-emerald-600">{{ number_format($votedCount) }}</h3>
                <p class="text-xs text-slate-500 mt-1">Pemilih telah menyelesaikan voting</p>
            </div>
        </div>

        <!-- Unvoted Count -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Belum Memilih</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl sm:text-3xl font-extrabold text-amber-600">{{ number_format($unvotedCount) }}</h3>
                <p class="text-xs text-slate-500 mt-1">Token siap pakai belum digunakan</p>
            </div>
        </div>

        <!-- Turnout -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Tingkat Partisipasi</span>
                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <h3 class="text-2xl sm:text-3xl font-extrabold text-indigo-600">{{ $turnoutPercentage }}%</h3>
                <div class="w-full bg-slate-100 rounded-full h-2 mt-2.5 overflow-hidden">
                    <div class="bg-indigo-600 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, $turnoutPercentage) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Positions & Progress Summary (2 Cols) -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Perolehan Suara per Jabatan</h2>
                    <p class="text-xs text-slate-500">6 Jabatan Pemilihan OSIS & MPK</p>
                </div>
                <a href="{{ route('admin.positions.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">
                    Kelola Jabatan &rarr;
                </a>
            </div>

            <div class="space-y-4">
                @forelse($positions as $pos)
                    <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 hover:bg-slate-50 transition">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2.5">
                                <span class="px-2 py-0.5 text-[10px] font-extrabold rounded-md {{ $pos->section === 'OSIS' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $pos->section }}
                                </span>
                                <span class="text-sm font-bold text-slate-800">{{ $pos->name }}</span>
                            </div>
                            <span class="text-xs font-semibold text-slate-600">{{ $pos->votes_count }} Suara</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                            @php
                                $percent = $totalVoters > 0 ? round(($pos->votes_count / $totalVoters) * 100, 1) : 0;
                            @endphp
                            <div class="h-2 rounded-full transition-all duration-500 {{ $pos->section === 'OSIS' ? 'bg-blue-600' : 'bg-emerald-600' }}" style="width: {{ min(100, $percent) }}%"></div>
                        </div>
                        <div class="flex justify-between items-center text-[11px] text-slate-400 mt-1.5">
                            <span>{{ $pos->candidates_count }} Kandidat Aktif</span>
                            <span>{{ $percent }}% partisipasi</span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-xs text-slate-400 py-6">Belum ada jabatan yang dibuat.</p>
                @endforelse
            </div>
        </div>

        <!-- Side Panel: Quick Actions & Audit Logs (1 Col) -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                <h2 class="text-base font-bold text-slate-900 mb-4">Aksi Cepat</h2>
                <div class="space-y-2.5">
                    <a href="{{ route('admin.tokens.index') }}" class="w-full flex items-center justify-between p-3 rounded-xl border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/50 transition text-slate-700 text-xs font-semibold group">
                        <div class="flex items-center space-x-2.5">
                            <span class="p-2 rounded-lg bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </span>
                            <span>Generate Token Massal</span>
                        </div>
                        <span class="text-slate-400 group-hover:translate-x-1 transition">&rarr;</span>
                    </a>

                    <a href="{{ route('admin.candidates.create') }}" class="w-full flex items-center justify-between p-3 rounded-xl border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/50 transition text-slate-700 text-xs font-semibold group">
                        <div class="flex items-center space-x-2.5">
                            <span class="p-2 rounded-lg bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </span>
                            <span>Tambah Kandidat Baru</span>
                        </div>
                        <span class="text-slate-400 group-hover:translate-x-1 transition">&rarr;</span>
                    </a>

                    <a href="{{ route('admin.tokens.export') }}" class="w-full flex items-center justify-between p-3 rounded-xl border border-slate-200 hover:border-emerald-300 hover:bg-emerald-50/50 transition text-slate-700 text-xs font-semibold group">
                        <div class="flex items-center space-x-2.5">
                            <span class="p-2 rounded-lg bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </span>
                            <span>Export CSV Token</span>
                        </div>
                        <span class="text-slate-400 group-hover:translate-x-1 transition">&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-slate-900">Aktivitas Terakhir</h2>
                    <a href="{{ route('admin.audit-logs.index') }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">Semua &rarr;</a>
                </div>

                <div class="space-y-3">
                    @forelse($recentAuditLogs as $log)
                        <div class="text-xs border-b border-slate-100 pb-2.5 last:border-0 last:pb-0">
                            <div class="flex items-center justify-between text-slate-400 text-[11px] mb-1">
                                <span class="font-semibold text-slate-700">{{ $log->action }}</span>
                                <span>{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-slate-600 truncate">{{ $log->description }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-4">Belum ada aktivitas audit.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
