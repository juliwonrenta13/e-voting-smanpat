<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\ElectionSetting;
use App\Models\Position;
use App\Models\User;
use App\Models\Voter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        User::updateOrCreate(
            ['email' => 'admin@smanpat.sch.id'],
            [
                'name' => 'Panitia Pemilu SMAN 4',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // 2. Election Setting
        ElectionSetting::updateOrCreate(
            ['id' => 1],
            [
                'title' => 'Pemilihan Pengurus OSIS & MPK SMAN 4',
                'academic_year' => '2026/2027',
                'status' => 'open',
                'start_at' => now()->startOfDay(),
                'end_at' => now()->addDays(7)->endOfDay(),
            ]
        );

        // 3. 6 Positions
        $positionsData = [
            ['section' => 'OSIS', 'name' => 'Ketua OSIS', 'order' => 1],
            ['section' => 'OSIS', 'name' => 'Sekretaris OSIS', 'order' => 2],
            ['section' => 'OSIS', 'name' => 'Bendahara OSIS', 'order' => 3],
            ['section' => 'MPK', 'name' => 'Ketua MPK', 'order' => 4],
            ['section' => 'MPK', 'name' => 'Sekretaris MPK', 'order' => 5],
            ['section' => 'MPK', 'name' => 'Bendahara MPK', 'order' => 6],
        ];

        $positions = [];
        foreach ($positionsData as $pos) {
            $positions[$pos['name']] = Position::updateOrCreate(
                ['name' => $pos['name']],
                [
                    'section' => $pos['section'],
                    'order' => $pos['order'],
                    'status' => 'active',
                ]
            );
        }

        // 4. Candidates for each position (2 per position = 12 candidates)
        $candidatesData = [
            'Ketua OSIS' => [
                [
                    'candidate_number' => 1,
                    'name' => 'Ahmad Fauzi Pratama',
                    'vision' => 'Mewujudkan OSIS SMAN 4 yang inklusif, inovatif, berintegritas, dan tanggap terhadap aspirasi generasi digital.',
                    'mission' => "1. Meningkatkan kualitas kegiatan ekstrakurikuler berbasis teknologi dan minat bakat.\n2. Mengoptimalkan wadah aspirasi siswa secara transparan dan berkala.\n3. Membangun kolaborasi erat antarekskul dan organisasi sekolah.",
                ],
                [
                    'candidate_number' => 2,
                    'name' => 'Clarissa Putri Maharani',
                    'vision' => 'Mewujudkan lingkungan sekolah yang harmonis, berprestasi unggul di kancah nasional, dan berkarakter Profil Pelajar Pancasila.',
                    'mission' => "1. Mengembangkan program mentoring akademik dan kepemimpinan sebaya.\n2. Menggalakkan kepedulian lingkungan hidup dan gerakan ramah lingkungan di sekolah.\n3. Memperkuat sinergi antara siswa, guru, dan alumni.",
                ],
            ],
            'Sekretaris OSIS' => [
                [
                    'candidate_number' => 1,
                    'name' => 'Dinda Aurelia Zahra',
                    'vision' => 'Administrasi OSIS yang transparan, terintegrasi digital, dan responsif.',
                    'mission' => "1. Digitalisasi sistem persuratan dan notulensi rapat organisasi.\n2. Publikasi berkala buletin kegiatan OSIS secara digital.\n3. Pengelolaan arsip kegiatan sekolah yang rapi dan mudah diakses.",
                ],
                [
                    'candidate_number' => 2,
                    'name' => 'Bintang Ramadhan',
                    'vision' => 'Sistem kesekretariatan yang rapi, akuntabel, dan mendukung koordinasi aktif antardivisi.',
                    'mission' => "1. Standarisasi format proposal dan laporan pertanggungjawaban kegiatan.\n2. Optimalisasi platform komunikasi internal pengurus OSIS.\n3. Pelatihan penulisan dan administrasi untuk perwakilan kelas.",
                ],
            ],
            'Bendahara OSIS' => [
                [
                    'candidate_number' => 1,
                    'name' => 'Fathir Nugraha',
                    'vision' => 'Pengelolaan keuangan OSIS yang amanah, transparan, dan produktif.',
                    'mission' => "1. Publikasi laporan kas berkala setiap akhir bulan melalui papan informasi digital.\n2. Efisiensi alokasi dana untuk mendukung program kerja siswa berprestasi.\n3. Inovasi penggalangan dana kreatif melalui kewirausahaan siswa.",
                ],
                [
                    'candidate_number' => 2,
                    'name' => 'Nadia Syahira',
                    'vision' => 'Akuntabilitas finansial tanpa celah dengan pencatatan digital real-time.',
                    'mission' => "1. Penerapan pembukuan berbasis aplikasi spreadsheet terpadu.\n2. Perencanaan anggaran berbasis prioritas kebutuhan peserta didik.\n3. Pendampingan administrasi kas untuk seluruh unit ekstrakurikuler.",
                ],
            ],
            'Ketua MPK' => [
                [
                    'candidate_number' => 1,
                    'name' => 'Rian Aditya Saputra',
                    'vision' => 'MPK sebagai pilar pengawas yang kritis, objektif, dan memperjuangkan hak suara seluruh siswa.',
                    'mission' => "1. Menjalankan fungsi pengawasan dan evaluasi program OSIS secara objektif.\n2. Mengadakan forum dengar pendapat umum bersama perwakilan kelas setiap semester.\n3. Menampung dan mengadvokasi kebutuhan sarana belajar siswa ke pihak sekolah.",
                ],
                [
                    'candidate_number' => 2,
                    'name' => 'Salma Kirana',
                    'vision' => 'Menjadikan MPK lembaga permusyawaratan yang berwibawa, solutif, dan mengedepankan musyawarah mufakat.',
                    'mission' => "1. Peningkatan disiplin dan peran aktif ketua kelas dalam legislasi sekolah.\n2. Sosialisasi regulasi dan tata tertib sekolah dengan pendekatan persuasif.\n3. Menjembatani aspirasi siswa dan dewan guru secara konstruktif.",
                ],
            ],
            'Sekretaris MPK' => [
                [
                    'candidate_number' => 1,
                    'name' => 'Kevin Pratama Wijaya',
                    'vision' => 'Dokumentasi dan legislasi MPK yang tertib, modern, dan terdokumentasi akurat.',
                    'mission' => "1. Penyusunan risalah sidang dan notulensi evaluasi kerja yang transparan.\n2. Pengarsipan dokumen kebijakan perwakilan kelas berbasis cloud storage.\n3. Penyelenggaraan kuesioner evaluasi kinerja berkala untuk siswa.",
                ],
                [
                    'candidate_number' => 2,
                    'name' => 'Hana Fitria Ningsih',
                    'vision' => 'Komunikasi legislatif yang efektif antara perwakilan kelas dan badan pengurus harian.',
                    'mission' => "1. Rekapitulasi usulan aspirasi kelas secara terstruktur dan cepat tanggap.\n2. Publikasi hasil sidang umum MPK kepada seluruh warga sekolah.\n3. Pembuatan agenda kerja dan jadwal pengawasan terkoordinasi.",
                ],
            ],
            'Bendahara MPK' => [
                [
                    'candidate_number' => 1,
                    'name' => 'Rizky Alamsyah',
                    'vision' => 'Transparansi anggaran pengawasan dan operasional perwakilan kelas yang bertanggung jawab.',
                    'mission' => "1. Manajemen anggaran sidang dan musyawarah perwakilan kelas yang hemat dan efektif.\n2. Laporan keuangan terbuka dan diaudit bersama pembina MPK.\n3. Pendampingan audit keuangan terhadap program kerja OSIS.",
                ],
                [
                    'candidate_number' => 2,
                    'name' => 'Tiara Anindya',
                    'vision' => 'Tata kelola anggaran MPK yang profesional, disiplin, dan terbuka.',
                    'mission' => "1. Penyusunan Rencana Anggaran Biaya (RAB) tahunan secara terperinci.\n2. Pemeriksaan realisasi anggaran setiap kegiatan kesiswaan.\n3. Penyediaan laporan pertanggungjawaban keuangan yang ringkas dan mudah dipahami.",
                ],
            ],
        ];

        foreach ($candidatesData as $posName => $cands) {
            $position = $positions[$posName];
            foreach ($cands as $c) {
                Candidate::updateOrCreate(
                    [
                        'position_id' => $position->id,
                        'candidate_number' => $c['candidate_number'],
                    ],
                    [
                        'name' => $c['name'],
                        'vision' => $c['vision'],
                        'mission' => $c['mission'],
                        'status' => 'active',
                    ]
                );
            }
        }

        // 5. Sample Voters & Tokens
        $sampleTokens = [
            ['token' => 'VOTE8881', 'status' => 'unused'],
            ['token' => 'VOTE8882', 'status' => 'unused'],
            ['token' => 'VOTE8883', 'status' => 'unused'],
            ['token' => 'VOTE8884', 'status' => 'unused'],
            ['token' => 'VOTE8885', 'status' => 'unused'],
            ['token' => 'VOTE8886', 'status' => 'unused'],
            ['token' => 'VOTE8887', 'status' => 'unused'],
            ['token' => 'VOTE8888', 'status' => 'unused'],
            ['token' => 'VOTE8889', 'status' => 'unused'],
            ['token' => 'VOTE8890', 'status' => 'unused'],
            ['token' => 'USED7777', 'status' => 'used', 'voted_at' => now()->subHours(2)],
            ['token' => 'DISA9999', 'status' => 'disabled'],
        ];

        foreach ($sampleTokens as $st) {
            Voter::updateOrCreate(
                ['token' => $st['token']],
                [
                    'status' => $st['status'],
                    'voted_at' => $st['voted_at'] ?? null,
                ]
            );
        }
    }
}
