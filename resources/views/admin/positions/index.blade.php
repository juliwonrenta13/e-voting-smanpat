@extends('layouts.admin')

@section('title', 'Manajemen Jabatan - E-Voting SMAN 4')
@section('page-title', 'Manajemen Jabatan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Daftar Jabatan Pemilihan</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola jabatan yang akan dipilih oleh pemilih pada bagian OSIS dan MPK</p>
        </div>
        <a href="{{ route('admin.positions.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Tambah Jabatan Baru</span>
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/75 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">Urutan</th>
                        <th class="px-5 py-3.5">Bagian</th>
                        <th class="px-5 py-3.5">Nama Jabatan</th>
                        <th class="px-5 py-3.5 text-center">Kandidat</th>
                        <th class="px-5 py-3.5 text-center">Suara Masuk</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($positions as $pos)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-5 py-4 font-mono font-bold text-slate-700">{{ $pos->order }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-extrabold {{ $pos->section === 'OSIS' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                                    {{ $pos->section }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-900 text-sm">{{ $pos->name }}</span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 font-bold text-[11px]">
                                    {{ $pos->candidates_count }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center font-bold text-slate-700">
                                {{ $pos->votes_count }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <form action="{{ route('admin.positions.toggle', $pos) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold transition {{ $pos->status === 'active' ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                        {{ $pos->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-4 text-right space-x-1.5 whitespace-nowrap">
                                <a href="{{ route('admin.positions.edit', $pos) }}" class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg inline-block transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>

                                @if($pos->votes_count === 0)
                                    <form action="{{ route('admin.positions.destroy', $pos) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus jabatan {{ $pos->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg inline-block transition" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-slate-400">Belum ada jabatan yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
