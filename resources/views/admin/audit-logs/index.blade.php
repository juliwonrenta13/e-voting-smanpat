@extends('layouts.admin')

@section('title', 'Audit Log Sistem - E-Voting SMAN 4')
@section('page-title', 'Audit Log Aktivitas')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-xl font-extrabold text-slate-900">Jejak Audit Aktivitas Admin</h1>
        <p class="text-xs text-slate-500 mt-1">Seluruh perubahan administratif dicatat secara transparan demi akuntabilitas pemilu</p>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="flex flex-1 flex-wrap items-center gap-2.5 w-full">
            <div class="relative flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari deskripsi atau IP..."
                    class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <select name="action" class="px-3 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                <option value="">-- Semua Jenis Aksi --</option>
                @foreach($actions as $act)
                    <option value="{{ $act }}" {{ $action === $act ? 'selected' : '' }}>{{ $act }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-semibold hover:bg-slate-900 transition">
                Filter
            </button>
            @if($search || $action)
                <a href="{{ route('admin.audit-logs.index') }}" class="px-3 py-2 text-xs text-slate-500 hover:text-slate-800">Reset</a>
            @endif
        </form>

        <span class="text-xs text-slate-400 whitespace-nowrap">Total Log: {{ $auditLogs->total() }} Entri</span>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/75 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Waktu</th>
                        <th class="px-5 py-3.5">Aktor / Admin</th>
                        <th class="px-5 py-3.5">Aksi</th>
                        <th class="px-5 py-3.5">Rincian Deskripsi</th>
                        <th class="px-5 py-3.5">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($auditLogs as $log)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-5 py-3.5 text-slate-500 whitespace-nowrap font-medium">
                                {{ $log->created_at->translatedFormat('d M Y - H:i:s') }}
                            </td>
                            <td class="px-5 py-3.5 font-bold text-slate-800 whitespace-nowrap">
                                {{ $log->user->name ?? 'Sistem / Anonim' }}
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-md font-mono text-[10px] font-extrabold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-700 max-w-md">
                                {{ $log->description }}
                            </td>
                            <td class="px-5 py-3.5 font-mono text-[11px] text-slate-400 whitespace-nowrap">
                                {{ $log->ip_address ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada catatan log aktivitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-slate-100">
            {{ $auditLogs->links() }}
        </div>
    </div>
</div>
@endsection
