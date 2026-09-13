<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara & Rekapitulasi Hasil Pemilihan - E-Voting SMAN 4</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 20px;
            font-size: 12pt;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            font-size: 14pt;
            text-transform: uppercase;
        }
        .header h1 {
            margin: 4px 0;
            font-size: 16pt;
            text-transform: uppercase;
        }
        .header p {
            margin: 0;
            font-size: 10pt;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            font-size: 11pt;
        }
        .meta-table td {
            padding: 4px;
        }
        .result-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 11pt;
        }
        .result-table th, .result-table td {
            border: 1px solid #000;
            padding: 6px 8px;
        }
        .result-table th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .signatures {
            width: 100%;
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signatures td {
            width: 50%;
            text-align: center;
            padding-top: 20px;
        }
        .sign-line {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; padding: 10px; background: #eef2ff; border: 1px solid #c7d2fe; text-align: center;">
        <button onclick="window.print()" style="padding: 8px 16px; font-weight: bold; background: #4f46e5; color: white; border: none; border-radius: 6px; cursor: pointer;">
            🖨️ Cetak Dokumen / Simpan PDF
        </button>
        <button onclick="window.close()" style="padding: 8px 16px; margin-left: 10px; background: #64748b; color: white; border: none; border-radius: 6px; cursor: pointer;">
            Tutup Jendela
        </button>
    </div>

    <!-- Letterhead -->
    <div class="header">
        <h2>PEMERINTAH DAERAH PROVINSI</h2>
        <h2>DINAS PENDIDIKAN DAN KEBUDAYAAN</h2>
        <h1>SMAN 4 — PANITIA PEMILIHAN OSIS & MPK</h1>
        <p>Alamat: Kampus SMAN 4 • Tahun Ajaran {{ $setting->academic_year }}</p>
    </div>

    <div style="text-align: center; margin-bottom: 20px;">
        <h3 style="margin: 0; text-decoration: underline; text-transform: uppercase;">BERITA ACARA & REKAPITULASI HASIL SUARA PEMILIHAN</h3>
        <p style="margin: 4px 0 0 0; font-size: 10pt;">Nomor: BA-PEMILU/{{ date('Y') }}/SMAN4/01</p>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 25%;"><strong>Nama Pemilihan</strong></td>
            <td style="width: 2%;">:</td>
            <td>{{ $setting->title }}</td>
            <td style="width: 20%;"><strong>Total Pemilih</strong></td>
            <td style="width: 2%;">:</td>
            <td>{{ number_format($totalVoters) }} Siswa</td>
        </tr>
        <tr>
            <td><strong>Tahun Ajaran</strong></td>
            <td>:</td>
            <td>{{ $setting->academic_year }}</td>
            <td><strong>Suara Masuk</strong></td>
            <td>:</td>
            <td>{{ number_format($totalVoted) }} Suara ({{ $turnout }}%)</td>
        </tr>
        <tr>
            <td><strong>Waktu Cetak</strong></td>
            <td>:</td>
            <td>{{ now()->translatedFormat('l, d F Y - H:i:s') }} WITA</td>
            <td><strong>Status Pemilihan</strong></td>
            <td>:</td>
            <td style="text-transform: uppercase;">{{ $setting->status }}</td>
        </tr>
    </table>

    <!-- Loop Positions -->
    @foreach($positions as $index => $pos)
        @php
            $posVotes = $pos->candidates->sum('votes_count');
        @endphp
        <div style="margin-bottom: 20px; page-break-inside: avoid;">
            <p style="font-weight: bold; margin: 0 0 6px 0;">
                {{ $index + 1 }}. Jabatan: {{ $pos->name }} (Bagian {{ $pos->section }}) — Total Suara: {{ $posVotes }}
            </p>
            <table class="result-table">
                <thead>
                    <tr>
                        <th style="width: 8%;" class="text-center">No. Urut</th>
                        <th style="width: 45%;">Nama Kandidat</th>
                        <th style="width: 15%;" class="text-center">Status</th>
                        <th style="width: 16%;" class="text-right">Perolehan Suara</th>
                        <th style="width: 16%;" class="text-right">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pos->candidates as $c)
                        @php
                            $cPercent = $posVotes > 0 ? round(($c->votes_count / $posVotes) * 100, 2) : 0;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $c->candidate_number }}</td>
                            <td><strong>{{ $c->name }}</strong></td>
                            <td class="text-center">{{ ucfirst($c->status) }}</td>
                            <td class="text-right">{{ number_format($c->votes_count) }}</td>
                            <td class="text-right">{{ $cPercent }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <!-- Signatures Section -->
    <table class="signatures">
        <tr>
            <td>
                Mengetahui,<br>
                <strong>Pembina OSIS / Kesiswaan</strong>
                <div class="sign-line">Drs. H. Pembina Sekolah, M.Pd.</div>
                NIP. 19750812 200003 1 002
            </td>
            <td>
                {{ date('d F Y') }}<br>
                <strong>Ketua Panitia Pemilihan</strong>
                <div class="sign-line">Ketua Panitia Pemilu</div>
                NISN. 0067891234
            </td>
        </tr>
    </table>

</body>
</html>
