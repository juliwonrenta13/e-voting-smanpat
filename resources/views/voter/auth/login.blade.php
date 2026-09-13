@extends('layouts.app')

@section('title', 'Bilik Suara Pemilih - E-Voting SMAN 4 TORAJA UTARA')

@section('body')
<div class="min-h-screen flex flex-col justify-between p-4 bg-gradient-to-b from-indigo-50/70 via-slate-50 to-slate-100">

    <!-- Top Bar -->
    <div class="max-w-md w-full mx-auto pt-6 flex items-center justify-between">
        <div class="flex items-center space-x-2.5">
            <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold shadow-md shadow-indigo-600/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="font-extrabold text-slate-800 text-sm tracking-tight">SMAN 4 TORAJA UTARA</span>
        </div>
        <a href="{{ route('admin.login') }}" class="text-[11px] text-slate-500 hover:text-indigo-600 font-semibold transition flex items-center space-x-1">
            <span>Admin</span>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>

    <!-- Login Container -->
    <div class="max-w-md w-full mx-auto my-auto py-8">
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl shadow-slate-200/50 border border-slate-200/70">
            <!-- Header Text -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-[11px] font-bold mb-3 border {{ $setting->isVotingOpen() ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-rose-50 text-rose-700 border-rose-200' }}">
                    <span class="w-2 h-2 rounded-full {{ $setting->isVotingOpen() ? 'bg-emerald-500 animate-ping' : 'bg-rose-500' }}"></span>
                    <span>{{ $setting->isVotingOpen() ? 'BILIK SUARA DIBUKA' : 'BILIK SUARA DITUTUP' }}</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-tight">
                    {{ $setting->title }}
                </h1>
                <p class="text-xs text-slate-500 mt-1.5">
                    Tahun Ajaran {{ $setting->academic_year }} • Masukkan token resmi Anda
                </p>
            </div>

            <!-- Alerts -->
            @if (session('error'))
                <div class="mb-5 bg-rose-50 border border-rose-200 text-rose-800 p-3.5 rounded-2xl text-xs font-medium flex items-start space-x-2.5">
                    <svg class="w-5 h-5 text-rose-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="flex-1">{{ session('error') }}</div>
                </div>
            @endif

            @if (session('info'))
                <div class="mb-5 bg-blue-50 border border-blue-200 text-blue-800 p-3.5 rounded-2xl text-xs font-medium flex items-start space-x-2.5">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1">{{ session('info') }}</div>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('voter.login.submit') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="token" class="block text-xs font-bold text-slate-700 mb-2">KODE TOKEN PEMILIH</label>
                    <div class="relative">
                        <input type="text" name="token" id="token" value="{{ old('token') }}" required autofocus
                            placeholder="Contoh: VOTE8881"
                            maxlength="20"
                            autocomplete="off"
                            class="w-full text-center tracking-[0.25em] uppercase font-mono font-black text-lg px-4 py-3.5 rounded-2xl border-2 border-slate-300 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-100 outline-none transition @error('token') border-rose-500 @enderror">
                    </div>
                    @error('token')
                        <p class="text-rose-600 text-xs mt-1.5 text-center font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" {{ !$setting->isVotingOpen() ? 'disabled' : '' }}
                    class="w-full py-3.5 px-4 rounded-2xl font-extrabold text-sm text-white shadow-lg transition duration-200 flex items-center justify-center space-x-2 {{ $setting->isVotingOpen() ? 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-600/30 cursor-pointer active:scale-[0.99]' : 'bg-slate-300 shadow-none cursor-not-allowed' }}">
                    <span>Masuk ke Bilik Suara</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <!-- Guide Cards -->
            <div class="mt-6 pt-5 border-t border-slate-100 space-y-2.5">
                <div class="flex items-start space-x-2.5 text-slate-600 text-[11px]">
                    <span class="w-4 h-4 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-[9px] flex-shrink-0 mt-0.5">1</span>
                    <p>Token dibagikan oleh panitia kelas/sekolah dan bersifat <strong>rahasia</strong>.</p>
                </div>
                <div class="flex items-start space-x-2.5 text-slate-600 text-[11px]">
                    <span class="w-4 h-4 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-[9px] flex-shrink-0 mt-0.5">2</span>
                    <p>Dalam 1 sesi Anda akan memilih <strong>6 jabatan</strong> (3 OSIS + 3 MPK).</p>
                </div>
                <div class="flex items-start space-x-2.5 text-slate-600 text-[11px]">
                    <span class="w-4 h-4 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-[9px] flex-shrink-0 mt-0.5">3</span>
                    <p>Satu token <strong>hanya berlaku 1 kali</strong> dan tidak dapat diulang.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Footer -->
    <div class="max-w-md w-full mx-auto pb-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} Panitia Pemilihan SMAN 4 • Sistem E-Voting Berintegritas
    </div>

</div>
@endsection
