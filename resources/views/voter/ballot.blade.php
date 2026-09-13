@extends('layouts.voter')

@section('title', 'Bilik Suara Digital - E-Voting SMAN 4')

@section('content')
<style>
    .candidate-card {
        transition: border-color 0.25s ease, box-shadow 0.25s ease, transform 0.25s ease;
    }
    .candidate-card:hover {
        transform: translateY(-3px);
    }
    .candidate-card .ballot-number-header {
        transition: background-color 0.2s;
    }
    /* When selected, scale takes precedence over hover translate */
    .candidate-card[style*="scale(1.03)"]:hover {
        transform: scale(1.03) !important;
    }
    .ballot-photo-banner svg ellipse,
    .ballot-photo-banner svg path {
        opacity: 0.45;
    }
</style>
<div class="space-y-10 pb-32">

    <!-- Hero Title -->
    <div class="bg-gradient-to-r from-indigo-900 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="relative z-10 max-w-2xl">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-500/30 text-indigo-200 border border-indigo-400/30 mb-2.5">
                SURAT SUARA DIGITAL RESMI
            </span>
            <h1 class="text-2xl sm:text-3xl font-black tracking-tight leading-tight">
                Pilihlah Pemimpin Terbaik untuk Almamater Kita
            </h1>
            <p class="text-xs sm:text-sm text-indigo-200 mt-2 leading-relaxed">
                Anda wajib memilih <strong>1 kandidat untuk masing-masing dari 6 jabatan</strong> (3 OSIS & 3 MPK). Pastikan seluruh pilihan terisi sebelum melakukan pengiriman suara secara final.
            </p>
        </div>
    </div>

    <form id="ballotForm" action="{{ route('voting.submit') }}" method="POST">
        @csrf

        <!-- Section 1: OSIS -->
        <div class="space-y-8">
            <div class="flex items-center space-x-3 pb-3 border-b-2 border-blue-600">
                <span class="w-8 h-8 rounded-xl bg-blue-600 text-white font-black text-sm flex items-center justify-center shadow-md shadow-blue-500/30">
                    A
                </span>
                <div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">BAGIAN 1: PENGURUS OSIS</h2>
                    <p class="text-xs text-slate-500">Pilih masing-masing 1 kandidat untuk 3 jabatan OSIS</p>
                </div>
            </div>

            @foreach($groupedPositions['OSIS'] as $position)
                <div id="position-container-{{ $position->id }}" class="space-y-4 pt-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2.5">
                            <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 text-xs font-black flex items-center justify-center">
                                {{ $position->order }}
                            </span>
                            <h3 class="text-base sm:text-lg font-extrabold text-slate-800">{{ $position->name }}</h3>
                        </div>
                        <span id="badge-status-{{ $position->id }}" class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 transition-all">
                            Belum Memilih
                        </span>
                    </div>

                    <!-- Candidate Cards Grid (Ballot Style) -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($position->activeCandidates as $candidate)
                            <div class="candidate-card group relative bg-white rounded-2xl border-3 border-slate-300 shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer flex flex-col overflow-hidden select-none"
                                style="border-width: 3px;"
                                data-position-id="{{ $position->id }}"
                                data-position-name="{{ $position->name }}"
                                data-candidate-id="{{ $candidate->id }}"
                                data-candidate-name="{{ $candidate->name }}"
                                data-candidate-number="{{ $candidate->candidate_number }}"
                                data-candidate-photo="{{ $candidate->photo_url ?? '' }}"
                                onclick="selectCandidate({{ $position->id }}, {{ $candidate->id }})">

                                <input type="radio" name="selections[{{ $position->id }}]" value="{{ $candidate->id }}" class="sr-only" id="cand-{{ $candidate->id }}">

                                <!-- Selected Indicator Checkmark -->
                                <div class="selected-badge absolute -top-3 -right-3 w-9 h-9 rounded-full bg-emerald-500 text-white font-bold flex items-center justify-center shadow-xl border-2 border-white scale-0 transition-transform duration-300 z-20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>

                                <!-- ── NUMBER HEADER ── -->
                                <div class="ballot-number-header bg-white border-b-2 border-slate-200 flex items-center justify-center py-3">
                                    <span class="text-3xl font-black text-slate-900 tracking-tighter leading-none">{{ $candidate->candidate_number }}</span>
                                </div>

                                <!-- ── RED BANNER with PHOTO ── -->
                                <div class="ballot-photo-banner relative bg-red-600 flex items-end justify-center overflow-hidden" style="min-height: 130px;">
                                    <!-- Background wave shape -->
                                    <div class="absolute inset-0 bg-gradient-to-b from-red-600 to-red-700"></div>

                                    @if($candidate->photo_url)
                                        <!-- Single photo centered -->
                                        <div class="relative z-10 flex items-end justify-center w-full h-full pt-3 pb-0">
                                            <img src="{{ $candidate->photo_url }}" alt="{{ $candidate->name }}"
                                                class="w-24 object-cover object-top rounded-t-full shadow-lg"
                                                style="height: 115px; margin-bottom: -1px;">
                                        </div>
                                    @else
                                        <!-- Silhouette placeholder -->
                                        <div class="relative z-10 flex items-end justify-center w-full gap-1 pt-2 pb-0">
                                            <!-- Main silhouette -->
                                            <svg viewBox="0 0 60 80" class="w-20 text-slate-300" style="margin-bottom:-1px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <ellipse cx="30" cy="20" rx="14" ry="16" />
                                                <path d="M5 80 Q5 50 30 50 Q55 50 55 80 Z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- ── CANDIDATE INFO ── -->
                                <div class="flex flex-col flex-1 p-3 bg-white">
                                    <!-- Position label -->
                                    <p class="text-[9px] uppercase font-extrabold tracking-widest text-slate-400 mb-0.5 text-center">{{ $position->name }}</p>

                                    <!-- Candidate Name -->
                                    <h4 class="text-[11px] sm:text-xs font-black text-slate-900 leading-snug text-center mb-2 line-clamp-2">{{ $candidate->name }}</h4>

                                    <!-- Vision snippet or decorative tags -->
                                    @if($candidate->vision)
                                        <p class="text-[9px] text-slate-400 italic text-center line-clamp-2 leading-snug mb-2">"{{ Str::limit($candidate->vision, 60) }}"</p>
                                    @endif

                                    <!-- Decorative "partai" style tags -->
                                    <div class="flex flex-wrap justify-center gap-1 mt-auto mb-2">
                                        <span class="inline-block px-1.5 py-0.5 rounded text-[8px] font-bold bg-red-100 text-red-700 border border-red-200">
                                            Kandidat
                                        </span>
                                        <span class="inline-block px-1.5 py-0.5 rounded text-[8px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            No. {{ $candidate->candidate_number }}
                                        </span>
                                    </div>

                                    <!-- Action Row -->
                                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                        <button type="button" onclick="event.stopPropagation(); showModal({{ json_encode($candidate) }}, '{{ $position->name }}')" class="text-indigo-600 hover:text-indigo-800 font-bold text-[9px] flex items-center space-x-0.5 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Visi &amp; Misi</span>
                                        </button>
                                        <span class="choice-label font-bold text-[9px] text-slate-400">Pilih</span>
                                    </div>
                                </div>

                                <!-- Selected overlay glow -->
                                <div class="selected-overlay absolute inset-0 pointer-events-none rounded-2xl ring-0 transition-all duration-300"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Section 2: MPK -->
        <div class="space-y-8 pt-8">
            <div class="flex items-center space-x-3 pb-3 border-b-2 border-emerald-600">
                <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-black text-sm flex items-center justify-center shadow-md shadow-emerald-500/30">
                    B
                </span>
                <div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">BAGIAN 2: PENGURUS MPK</h2>
                    <p class="text-xs text-slate-500">Pilih masing-masing 1 kandidat untuk 3 jabatan MPK</p>
                </div>
            </div>

            @foreach($groupedPositions['MPK'] as $position)
                <div id="position-container-{{ $position->id }}" class="space-y-4 pt-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2.5">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 text-xs font-black flex items-center justify-center">
                                {{ $position->order }}
                            </span>
                            <h3 class="text-base sm:text-lg font-extrabold text-slate-800">{{ $position->name }}</h3>
                        </div>
                        <span id="badge-status-{{ $position->id }}" class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 transition-all">
                            Belum Memilih
                        </span>
                    </div>

                    <!-- Candidate Cards Grid (Ballot Style) -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($position->activeCandidates as $candidate)
                            <div class="candidate-card group relative bg-white rounded-2xl border-3 border-slate-300 shadow-md hover:shadow-xl transition-all duration-300 cursor-pointer flex flex-col overflow-hidden select-none"
                                style="border-width: 3px;"
                                data-position-id="{{ $position->id }}"
                                data-position-name="{{ $position->name }}"
                                data-candidate-id="{{ $candidate->id }}"
                                data-candidate-name="{{ $candidate->name }}"
                                data-candidate-number="{{ $candidate->candidate_number }}"
                                data-candidate-photo="{{ $candidate->photo_url ?? '' }}"
                                onclick="selectCandidate({{ $position->id }}, {{ $candidate->id }})">

                                <input type="radio" name="selections[{{ $position->id }}]" value="{{ $candidate->id }}" class="sr-only" id="cand-{{ $candidate->id }}">

                                <!-- Selected Indicator Checkmark -->
                                <div class="selected-badge absolute -top-3 -right-3 w-9 h-9 rounded-full bg-emerald-500 text-white font-bold flex items-center justify-center shadow-xl border-2 border-white scale-0 transition-transform duration-300 z-20">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>

                                <!-- ── NUMBER HEADER ── -->
                                <div class="ballot-number-header bg-white border-b-2 border-slate-200 flex items-center justify-center py-3">
                                    <span class="text-3xl font-black text-slate-900 tracking-tighter leading-none">{{ $candidate->candidate_number }}</span>
                                </div>

                                <!-- ── RED BANNER with PHOTO ── -->
                                <div class="ballot-photo-banner relative bg-red-600 flex items-end justify-center overflow-hidden" style="min-height: 130px;">
                                    <div class="absolute inset-0 bg-gradient-to-b from-red-600 to-red-700"></div>

                                    @if($candidate->photo_url)
                                        <div class="relative z-10 flex items-end justify-center w-full h-full pt-3 pb-0">
                                            <img src="{{ $candidate->photo_url }}" alt="{{ $candidate->name }}"
                                                class="w-24 object-cover object-top rounded-t-full shadow-lg"
                                                style="height: 115px; margin-bottom: -1px;">
                                        </div>
                                    @else
                                        <div class="relative z-10 flex items-end justify-center w-full gap-1 pt-2 pb-0">
                                            <svg viewBox="0 0 60 80" class="w-20 text-slate-300" style="margin-bottom:-1px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <ellipse cx="30" cy="20" rx="14" ry="16" />
                                                <path d="M5 80 Q5 50 30 50 Q55 50 55 80 Z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- ── CANDIDATE INFO ── -->
                                <div class="flex flex-col flex-1 p-3 bg-white">
                                    <p class="text-[9px] uppercase font-extrabold tracking-widest text-slate-400 mb-0.5 text-center">{{ $position->name }}</p>
                                    <h4 class="text-[11px] sm:text-xs font-black text-slate-900 leading-snug text-center mb-2 line-clamp-2">{{ $candidate->name }}</h4>

                                    @if($candidate->vision)
                                        <p class="text-[9px] text-slate-400 italic text-center line-clamp-2 leading-snug mb-2">"{{ Str::limit($candidate->vision, 60) }}"</p>
                                    @endif

                                    <div class="flex flex-wrap justify-center gap-1 mt-auto mb-2">
                                        <span class="inline-block px-1.5 py-0.5 rounded text-[8px] font-bold bg-red-100 text-red-700 border border-red-200">
                                            Kandidat
                                        </span>
                                        <span class="inline-block px-1.5 py-0.5 rounded text-[8px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                            No. {{ $candidate->candidate_number }}
                                        </span>
                                    </div>

                                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                        <button type="button" onclick="event.stopPropagation(); showModal({{ json_encode($candidate) }}, '{{ $position->name }}')" class="text-emerald-600 hover:text-emerald-800 font-bold text-[9px] flex items-center space-x-0.5 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>Visi &amp; Misi</span>
                                        </button>
                                        <span class="choice-label font-bold text-[9px] text-slate-400">Pilih</span>
                                    </div>
                                </div>

                                <div class="selected-overlay absolute inset-0 pointer-events-none rounded-2xl ring-0 transition-all duration-300"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </form>
</div>

<!-- Sticky Floating Bottom Progress Bar -->
<div class="fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-md border-t border-slate-200 shadow-2xl p-4 z-40">
    <div class="max-w-4xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Progress Info -->
        <div class="w-full sm:w-auto flex items-center justify-between sm:justify-start space-x-4">
            <div>
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Status Pemilihan:</span>
                    <span id="selectedCountText" class="text-sm font-black text-indigo-600">0 dari {{ $positions->count() }} Jabatan Terpilih</span>
                </div>
                <div class="w-48 sm:w-64 bg-slate-100 rounded-full h-2.5 mt-1.5 overflow-hidden">
                    <div id="progressBar" class="bg-indigo-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>

            <!-- Unfinished Jump Button -->
            <button id="jumpUnfinishedBtn" type="button" onclick="jumpToUnfinished()" class="hidden text-xs text-amber-700 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-xl font-bold hover:bg-amber-100 transition flex items-center space-x-1">
                <span>Lengkapi</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </button>
        </div>

        <!-- Review & Submit Trigger Button -->
        <button id="openReviewBtn" type="button" onclick="openReviewModal()" disabled
            class="w-full sm:w-auto px-6 py-3 rounded-2xl font-black text-sm text-white transition-all shadow-lg flex items-center justify-center space-x-2 bg-slate-300 shadow-none cursor-not-allowed">
            <span>Tinjau & Kirim Suara (6/6)</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>
</div>

<!-- Modal 1: Vision & Mission Preview -->
<div id="vmModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto relative">
        <button onclick="closeVmModal()" class="absolute top-5 right-5 p-2 rounded-full text-slate-400 hover:text-slate-700 hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="flex items-center space-x-4 mb-5 pb-4 border-b border-slate-100">
            <div id="modalCandidateNumber" class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-black text-base flex items-center justify-center shadow-md">
                1
            </div>
            <div>
                <span id="modalPositionName" class="text-[11px] font-extrabold uppercase tracking-wider text-indigo-600">Ketua OSIS</span>
                <h3 id="modalCandidateName" class="text-base font-black text-slate-900 leading-tight">Nama Kandidat</h3>
            </div>
        </div>

        <div class="space-y-4 text-xs text-slate-700 leading-relaxed">
            <div>
                <h4 class="font-extrabold text-slate-900 text-xs mb-1 uppercase tracking-wider flex items-center space-x-1.5">
                    <span>🎯</span>
                    <span>Visi</span>
                </h4>
                <p id="modalCandidateVision" class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 text-slate-700 italic">-</p>
            </div>

            <div>
                <h4 class="font-extrabold text-slate-900 text-xs mb-1 uppercase tracking-wider flex items-center space-x-1.5">
                    <span>📋</span>
                    <span>Misi</span>
                </h4>
                <div id="modalCandidateMission" class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 text-slate-700 whitespace-pre-line">-</div>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end">
            <button type="button" onclick="closeVmModal()" class="px-5 py-2.5 rounded-xl bg-slate-800 text-white text-xs font-bold hover:bg-slate-900 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal 2: Final Review & Confirmation Modal -->
<div id="reviewModal" class="fixed inset-0 bg-slate-900/70 backdrop-blur-md z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-2xl w-full shadow-2xl border border-slate-100 max-h-[95vh] overflow-y-auto relative">
        <button onclick="closeReviewModal()" class="absolute top-5 right-5 p-2 rounded-full text-slate-400 hover:text-slate-700 hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="text-center mb-6">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 mb-2">
                LANGKAH AKHIR: KONFIRMASI SUARA
            </span>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900">Periksa Kembali 6 Pilihan Anda</h2>
            <p class="text-xs text-slate-500 mt-1">Pastikan seluruh pilihan di bawah ini sesuai dengan hati nurani Anda</p>
        </div>

        <!-- 6 Selected Candidates Summary List -->
        <div id="reviewList" class="space-y-2.5 mb-6">
            <!-- Populated via Javascript -->
        </div>

        <!-- Final Notice Box -->
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs mb-6 space-y-2">
            <div class="flex items-center space-x-2 font-black text-amber-800">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>PERINGATAN PENTING:</span>
            </div>
            <p class="text-[11px] leading-relaxed">
                Suara Anda akan disimpan secara permanen dalam satu transaksi atomik. Sekali tombol <strong>"Kirim Surat Suara Sekarang"</strong> ditekan, token Anda akan berstatus <em>used</em> dan Anda <strong>tidak dapat memilih ulang ataupun mengubah pilihan</strong>.
            </p>
            <label class="flex items-start space-x-2.5 pt-2 cursor-pointer">
                <input type="checkbox" id="confirmCheckbox" onchange="toggleFinalSubmitBtn()" class="w-4 h-4 mt-0.5 rounded text-indigo-600 border-amber-300 focus:ring-indigo-500">
                <span class="text-xs font-bold text-amber-950">
                    Saya menyatakan telah memeriksa dan menyetujui seluruh 6 pilihan saya.
                </span>
            </label>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between gap-3">
            <button type="button" onclick="closeReviewModal()" class="px-5 py-3 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-50 transition">
                &larr; Ubah Pilihan
            </button>

            <button id="finalSubmitBtn" type="button" onclick="executeSubmit()" disabled
                class="flex-1 px-6 py-3 rounded-xl font-extrabold text-xs sm:text-sm text-white transition-all shadow-lg flex items-center justify-center space-x-2 bg-slate-300 shadow-none cursor-not-allowed">
                <span id="finalSubmitText">Kirim Surat Suara Sekarang</span>
                <svg id="finalSubmitIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <!-- Spinner -->
                <svg id="finalSubmitSpinner" class="w-5 h-5 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
    const totalPositions = {{ $positions->count() }};
    const selections = {}; // { positionId: candidateData }

    function selectCandidate(positionId, candidateId) {
        // Find candidate card
        const card = document.querySelector(`.candidate-card[data-position-id="${positionId}"][data-candidate-id="${candidateId}"]`);
        if (!card) return;

        // Check radio input
        const radio = document.getElementById(`cand-${candidateId}`);
        if (radio) radio.checked = true;

        // Deselect other cards in same position
        document.querySelectorAll(`.candidate-card[data-position-id="${positionId}"]`).forEach(c => {
            c.style.borderColor = '';
            c.style.boxShadow = '';
            c.style.transform = '';
            const badge = c.querySelector('.selected-badge');
            if (badge) badge.classList.add('scale-0');
            const lbl = c.querySelector('.choice-label');
            if (lbl) {
                lbl.textContent = 'Pilih';
                lbl.className = 'choice-label font-bold text-[9px] text-slate-400';
            }
            const overlay = c.querySelector('.selected-overlay');
            if (overlay) overlay.style.boxShadow = '';
        });

        // Highlight selected card — red ballot border
        card.style.borderColor = '#dc2626';
        card.style.boxShadow = '0 0 0 4px rgba(220,38,38,0.15), 0 8px 24px rgba(220,38,38,0.2)';
        card.style.transform = 'scale(1.03)';
        const badge = card.querySelector('.selected-badge');
        if (badge) badge.classList.remove('scale-0');
        const lbl = card.querySelector('.choice-label');
        if (lbl) {
            lbl.textContent = '✓ Terpilih';
            lbl.className = 'choice-label font-bold text-[9px] text-emerald-600';
        }

        // Update position status badge
        const posBadge = document.getElementById(`badge-status-${positionId}`);
        if (posBadge) {
            posBadge.textContent = '✓ Sudah Memilih';
            posBadge.className = 'px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200';
        }

        // Save selection
        selections[positionId] = {
            positionId: positionId,
            positionName: card.dataset.positionName,
            candidateId: candidateId,
            candidateName: card.dataset.candidateName,
            candidateNumber: card.dataset.candidateNumber,
            candidatePhoto: card.dataset.candidatePhoto
        };

        updateProgress();
    }

    function updateProgress() {
        const count = Object.keys(selections).length;
        const percent = (count / totalPositions) * 100;

        document.getElementById('progressBar').style.width = `${percent}%`;
        document.getElementById('selectedCountText').textContent = `${count} dari ${totalPositions} Jabatan Terpilih`;

        const openBtn = document.getElementById('openReviewBtn');
        const jumpBtn = document.getElementById('jumpUnfinishedBtn');

        if (count === totalPositions) {
            openBtn.disabled = false;
            openBtn.className = 'w-full sm:w-auto px-6 py-3 rounded-2xl font-black text-sm text-white transition-all shadow-lg flex items-center justify-center space-x-2 bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/30 cursor-pointer active:scale-[0.99]';
            jumpBtn.classList.add('hidden');
        } else {
            openBtn.disabled = true;
            openBtn.className = 'w-full sm:w-auto px-6 py-3 rounded-2xl font-black text-sm text-white transition-all shadow-lg flex items-center justify-center space-x-2 bg-slate-300 shadow-none cursor-not-allowed';
            jumpBtn.classList.remove('hidden');
        }
    }

    function jumpToUnfinished() {
        const allPosContainers = document.querySelectorAll('[id^="position-container-"]');
        for (let container of allPosContainers) {
            const posId = container.id.replace('position-container-', '');
            if (!selections[posId]) {
                container.scrollIntoView({ behavior: 'smooth', block: 'center' });
                container.classList.add('ring-4', 'ring-amber-300', 'rounded-2xl');
                setTimeout(() => container.classList.remove('ring-4', 'ring-amber-300'), 1500);
                break;
            }
        }
    }

    // Vision Mission Modal
    function showModal(candidate, positionName) {
        document.getElementById('modalCandidateNumber').textContent = candidate.candidate_number;
        document.getElementById('modalPositionName').textContent = positionName;
        document.getElementById('modalCandidateName').textContent = candidate.name;
        document.getElementById('modalCandidateVision').textContent = candidate.vision || 'Visi tidak disertakan.';
        document.getElementById('modalCandidateMission').textContent = candidate.mission || 'Misi tidak disertakan.';
        document.getElementById('vmModal').classList.remove('hidden');
    }

    function closeVmModal() {
        document.getElementById('vmModal').classList.add('hidden');
    }

    // Review Modal
    function openReviewModal() {
        if (Object.keys(selections).length !== totalPositions) {
            alert('Mohon lengkapi seluruh ' + totalPositions + ' pilihan jabatan terlebih dahulu.');
            return;
        }

        const reviewList = document.getElementById('reviewList');
        reviewList.innerHTML = '';

        Object.values(selections).forEach(sel => {
            const item = document.createElement('div');
            item.className = 'p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs';
            item.innerHTML = `
                <div class="flex items-center space-x-3">
                    <span class="w-7 h-7 rounded-lg bg-indigo-600 text-white font-black text-xs flex items-center justify-center shadow-sm">
                        ${sel.candidateNumber}
                    </span>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase">${sel.positionName}</p>
                        <h4 class="font-extrabold text-slate-900 text-sm">${sel.candidateName}</h4>
                    </div>
                </div>
                <span class="text-emerald-600 font-extrabold text-xs">Pilihan Sah</span>
            `;
            reviewList.appendChild(item);
        });

        // Reset confirm checkbox
        document.getElementById('confirmCheckbox').checked = false;
        toggleFinalSubmitBtn();

        document.getElementById('reviewModal').classList.remove('hidden');
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').classList.add('hidden');
    }

    function toggleFinalSubmitBtn() {
        const checkbox = document.getElementById('confirmCheckbox');
        const btn = document.getElementById('finalSubmitBtn');

        if (checkbox.checked) {
            btn.disabled = false;
            btn.className = 'flex-1 px-6 py-3 rounded-xl font-extrabold text-xs sm:text-sm text-white transition-all shadow-lg flex items-center justify-center space-x-2 bg-indigo-600 hover:bg-indigo-700 shadow-indigo-600/30 cursor-pointer active:scale-[0.99]';
        } else {
            btn.disabled = true;
            btn.className = 'flex-1 px-6 py-3 rounded-xl font-extrabold text-xs sm:text-sm text-white transition-all shadow-lg flex items-center justify-center space-x-2 bg-slate-300 shadow-none cursor-not-allowed';
        }
    }

    function executeSubmit() {
        const btn = document.getElementById('finalSubmitBtn');
        const text = document.getElementById('finalSubmitText');
        const icon = document.getElementById('finalSubmitIcon');
        const spinner = document.getElementById('finalSubmitSpinner');

        // Prevent double click
        btn.disabled = true;
        text.textContent = 'Menyimpan Suara Atomik...';
        icon.classList.add('hidden');
        spinner.classList.remove('hidden');

        document.getElementById('ballotForm').submit();
    }
</script>
@endsection
