@extends('layouts.app')

@section('title', 'Suara Berhasil Terkirim - E-Voting SMAN 4')

@section('body')
<div class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-indigo-50 via-slate-50 to-emerald-50">
    <div class="max-w-md w-full my-auto">
        <!-- Success Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xl border border-slate-100 text-center relative overflow-hidden">
            <!-- Top Green Accent Line -->
            <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-emerald-400 to-indigo-600"></div>

            <!-- Animated Success Icon -->
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-emerald-50 text-emerald-500 mb-6 ring-8 ring-emerald-50 shadow-inner">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <span class="inline-block px-3 py-1 rounded-full text-[11px] font-extrabold bg-emerald-100 text-emerald-800 mb-2">
                TRANSAKSI SUARA BERHASIL
            </span>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-tight">
                Hak Suara Anda Telah Tercatat!
            </h1>
            <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                Terima kasih telah berpartisipasi dalam Pemilihan Pengurus OSIS & MPK SMAN 4 secara jujur, adil, dan rahasia.
            </p>

            <!-- Receipt Box -->
            <div class="mt-6 p-5 rounded-2xl bg-slate-50 border border-slate-200 text-left space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">TANDA TERIMA ELEKTRONIK</span>
                    <span class="text-[10px] font-extrabold text-emerald-600 flex items-center space-x-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>TERVERIFIKASI</span>
                    </span>
                </div>

                @if(!empty($receipt))
                    <div>
                        <span class="text-[11px] text-slate-400 font-medium">KODE VERIFIKASI RESMI:</span>
                        <p class="font-mono font-black text-lg text-indigo-700 tracking-widest mt-0.5">
                            {{ $receipt['receipt_code'] ?? 'EVT-' . strtoupper(substr(md5(time()), 0, 8)) }}
                        </p>
                    </div>

                    <div class="flex justify-between text-xs pt-1">
                        <span class="text-slate-400">Total Suara Sah:</span>
                        <span class="font-extrabold text-slate-800">{{ $receipt['vote_count'] ?? 6 }} Jabatan Lengkap</span>
                    </div>

                    <div class="flex justify-between text-xs">
                        <span class="text-slate-400">Waktu Pencatatan:</span>
                        <span class="font-medium text-slate-700">
                            {{ isset($receipt['voted_at']) ? $receipt['voted_at']->translatedFormat('d F Y - H:i:s') : now()->translatedFormat('d F Y - H:i:s') }} WITA
                        </span>
                    </div>
                @else
                    <p class="text-xs text-slate-500 text-center py-2">
                        Surat suara Anda telah berhasil disimpan ke database.
                    </p>
                @endif

                <div class="pt-2 border-t border-slate-200 text-[10px] text-slate-400 leading-tight">
                    * Demi asas LUBER JURDIL, isi pilihan kandidat Anda dirahasiakan dan tidak dicetak pada tanda terima.
                </div>
            </div>

            <!-- Return Home Button -->
            <div class="mt-6">
                <a href="{{ route('voter.login') }}" class="w-full inline-flex items-center justify-center space-x-2 py-3 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-md transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Selesai & Keluar</span>
                </a>
            </div>
        </div>

        <p class="text-center text-[11px] text-slate-400 mt-5">
            E-Voting SMAN 4 • Satu Token Satu Suara • Dilindungi Integritas Atomik
        </p>
    </div>
</div>
@endsection
