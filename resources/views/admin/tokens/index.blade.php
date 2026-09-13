@extends('layouts.admin')

@section('title', 'Manajemen Token Pemilih - E-Voting SMAN 4')
@section('page-title', 'Manajemen Token Pemilih')

@section('content')
<div class="space-y-6">
    <!-- Header with Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Token Pemilih (Hak Suara)</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola token unik pemilihan siswa. 1 Token = 1 Hak Suara (6 Pilihan Jabatan)</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5">
            <form action="{{ route('admin.tokens.generate-single') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-bold transition flex items-center space-x-1.5 shadow-sm">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+1 Token</span>
                </button>
            </form>

            <button onclick="document.getElementById('batchModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span>Generate Massal</span>
            </button>

            <a href="{{ route('admin.tokens.export', ['status' => $status]) }}" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-md shadow-emerald-600/20 transition flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Export CSV</span>
            </a>
        </div>
    </div>

    <!-- Status Stats Badges -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="{{ route('admin.tokens.index') }}" class="p-4 rounded-2xl border transition {{ empty($status) ? 'bg-indigo-50 border-indigo-200 ring-2 ring-indigo-500/20' : 'bg-white border-slate-200/80 hover:bg-slate-50' }}">
            <span class="text-[11px] font-bold text-slate-500 uppercase">Semua Token</span>
            <p class="text-xl font-extrabold text-slate-900 mt-1">{{ number_format($counts['total']) }}</p>
        </a>
        <a href="{{ route('admin.tokens.index', ['status' => 'unused']) }}" class="p-4 rounded-2xl border transition {{ $status === 'unused' ? 'bg-amber-50 border-amber-200 ring-2 ring-amber-500/20' : 'bg-white border-slate-200/80 hover:bg-slate-50' }}">
            <span class="text-[11px] font-bold text-amber-700 uppercase">Belum Digunakan</span>
            <p class="text-xl font-extrabold text-amber-600 mt-1">{{ number_format($counts['unused']) }}</p>
        </a>
        <a href="{{ route('admin.tokens.index', ['status' => 'used']) }}" class="p-4 rounded-2xl border transition {{ $status === 'used' ? 'bg-emerald-50 border-emerald-200 ring-2 ring-emerald-500/20' : 'bg-white border-slate-200/80 hover:bg-slate-50' }}">
            <span class="text-[11px] font-bold text-emerald-700 uppercase">Sudah Memilih</span>
            <p class="text-xl font-extrabold text-emerald-600 mt-1">{{ number_format($counts['used']) }}</p>
        </a>
        <a href="{{ route('admin.tokens.index', ['status' => 'disabled']) }}" class="p-4 rounded-2xl border transition {{ $status === 'disabled' ? 'bg-slate-100 border-slate-300 ring-2 ring-slate-500/20' : 'bg-white border-slate-200/80 hover:bg-slate-50' }}">
            <span class="text-[11px] font-bold text-slate-600 uppercase">Dinonaktifkan</span>
            <p class="text-xl font-extrabold text-slate-600 mt-1">{{ number_format($counts['disabled']) }}</p>
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
        <form action="{{ route('admin.tokens.index') }}" method="GET" class="flex flex-1 items-center space-x-2 w-full">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari kode token..."
                    class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <button type="submit" class="px-3.5 py-2 bg-slate-800 text-white rounded-xl text-xs font-semibold hover:bg-slate-900 transition">
                Cari
            </button>
            @if($search)
                <a href="{{ route('admin.tokens.index', ['status' => $status]) }}" class="px-3 py-2 text-xs text-slate-500 hover:text-slate-800">Reset</a>
            @endif
        </form>

        <span class="text-xs text-slate-400 whitespace-nowrap">Total Halaman: {{ $voters->total() }} Token</span>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/75 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-3.5">No</th>
                        <th class="px-5 py-3.5">Kode Token</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5">Waktu Voting</th>
                        <th class="px-5 py-3.5">Dibuat Pada</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($voters as $index => $v)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-5 py-3.5 font-mono text-slate-400">{{ $voters->firstItem() + $index }}</td>
                            <td class="px-5 py-3.5">
                                <span class="font-mono font-extrabold text-sm tracking-widest text-slate-900 px-2 py-1 bg-slate-100 rounded-lg border border-slate-200">
                                    {{ $v->token }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($v->status === 'unused')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-700 border border-amber-200">
                                        Belum Dipakai
                                    </span>
                                @elseif($v->status === 'used')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Sudah Memilih
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-600 border border-slate-200">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-slate-600 font-medium">
                                {{ $v->voted_at ? $v->voted_at->translatedFormat('d M Y H:i:s') : '-' }}
                            </td>
                            <td class="px-5 py-3.5 text-slate-400">
                                {{ $v->created_at->translatedFormat('d M Y H:i') }}
                            </td>
                            <td class="px-5 py-3.5 text-right space-x-1.5 whitespace-nowrap">
                                @if($v->status !== 'used')
                                    <!-- Toggle Active/Disable -->
                                    <form action="{{ route('admin.tokens.toggle', $v) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-semibold transition {{ $v->status === 'unused' ? 'text-amber-700 hover:bg-amber-50' : 'text-emerald-700 hover:bg-emerald-50' }}" title="Ubah Status">
                                            {{ $v->status === 'unused' ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    <!-- Delete Token -->
                                    <form action="{{ route('admin.tokens.destroy', $v) }}" method="POST" class="inline" onsubmit="return confirm('Hapus token {{ $v->token }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-1 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 transition" title="Hapus">
                                            Hapus
                                        </button>
                                    </form>
                                @else
                                    <!-- Reset Token Option (Used token) -->
                                    <form action="{{ route('admin.tokens.reset', $v) }}" method="POST" class="inline" onsubmit="return confirm('PERINGATAN: Mereset token ini akan menghapus data suara pemilih terkait dan mengembalikan status menjadi unused. Lanjutkan?')">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 transition" title="Reset Token">
                                            Reset Suara
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-400">Tidak ada token pemilih ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-5 py-4 border-t border-slate-100">
            {{ $voters->links() }}
        </div>
    </div>
</div>

<!-- Modal Generate Batch -->
<div id="batchModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-100 relative">
        <button onclick="document.getElementById('batchModal').classList.add('hidden')" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="mb-5">
            <h2 class="text-lg font-bold text-slate-900">Generate Token Massal</h2>
            <p class="text-xs text-slate-500 mt-1">Buat banyak token unik acak sekaligus untuk dibagikan ke siswa</p>
        </div>

        <form action="{{ route('admin.tokens.generate-batch') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="count" class="block text-xs font-bold text-slate-700 mb-1.5">Jumlah Token</label>
                <input type="number" name="count" id="count" value="50" min="1" max="1000" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                <p class="text-[11px] text-slate-400 mt-1">Maksimal 1.000 token per batch.</p>
            </div>

            <div>
                <label for="length" class="block text-xs font-bold text-slate-700 mb-1.5">Panjang Karakter Token</label>
                <select name="length" id="length" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    <option value="8" selected>8 Karakter (Contoh: V8K2MN9P) - Standar</option>
                    <option value="10">10 Karakter (Contoh: 7W4K9X2MNP)</option>
                    <option value="12">12 Karakter</option>
                </select>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end space-x-3">
                <button type="button" onclick="document.getElementById('batchModal').classList.add('hidden')" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 text-xs font-semibold hover:bg-slate-50 transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition">
                    Mulai Generate
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
