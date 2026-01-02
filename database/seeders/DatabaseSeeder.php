<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mentor;
use App\Models\Kelas;
use App\Models\Materi;
use App\Models\SubMateri;
use App\Models\Video;
use App\Models\Dokumen;
use App\Models\MetodePembayaran;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\ProgressSubMateri;
use App\Models\Review;
use App\Models\Report;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ==========================================
        // 1. DATA MASTER (FIXED)
        // ==========================================
        
        // Buat 1 Akun Admin Pasti (untuk Login Developer)
        User::factory()->create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@kelaskita.com',
            'password' => bcrypt('password'), // password default
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Buat 1 Akun Mentor Pasti
        $mentorUser = User::factory()->create([
            'first_name' => 'Sandhika',
            'last_name' => 'Galih',
            'username' => 'sandhikagalih',
            'email' => 'mentor@kelaskita.com',
            'password' => bcrypt('password'),
            'role' => 'mentor',
            'status' => 'active',
        ]);
        
        $mentorFixed = Mentor::factory()->create([
            'id_user' => $mentorUser->id_user,
            'status' => 'approved'
        ]);

        // Buat 1 Akun Student Pasti
        $studentFixed = User::factory()->create([
            'first_name' => 'Budi',
            'last_name' => 'Santoso',
            'username' => 'budigamer',
            'email' => 'student@kelaskita.com',
            'password' => bcrypt('password'),
            'role' => 'student',
            'status' => 'active',
        ]);

        // Buat Metode Pembayaran
        $methods = ['BCA Virtual Account', 'Mandiri Transfer', 'GoPay', 'OVO', 'Credit Card'];
        foreach ($methods as $m) {
            MetodePembayaran::factory()->create(['nama_metode' => $m]);
        }


        // ==========================================
        // 2. GENERATE MASSIVE USERS
        // ==========================================
        
        // Buat 10 Mentor Random Tambahan
        $randomMentors = Mentor::factory()->count(10)->create();

        // Buat 40 Student Random Tambahan
        $randomStudents = User::factory()->count(40)->state(['role' => 'student'])->create();
        
        // Gabungkan student fixed & random untuk pool pembeli
        $allStudents = $randomStudents->push($studentFixed);
        $allMentors = $randomMentors->push($mentorFixed);


        // ==========================================
        // 3. GENERATE COURSE STRUCTURE (Kelas -> Bab -> Video)
        // ==========================================
        
        $allKelas = collect();

        foreach ($allMentors as $mentor) {
            // Setiap mentor bikin 2-3 kelas
            $kelasMentor = Kelas::factory()->count(rand(2, 3))->create([
                'id_mentor' => $mentor->id_mentor
            ]);

            foreach ($kelasMentor as $kelas) {
                $allKelas->push($kelas);

                // Setiap kelas punya 3-5 Bab (Materi)
                $materis = Materi::factory()->count(rand(3, 5))->create([
                    'id_kelas' => $kelas->id_kelas
                ]);

                foreach ($materis as $materi) {
                    // Setiap Bab punya 2-4 Pelajaran (SubMateri)
                    // Kita mix antara Video dan Dokumen
                    for ($i = 1; $i <= rand(2, 4); $i++) {
                        $isVideo = rand(0, 1); // 50% chance video, 50% dokumen
                        
                        $assetId = null;
                        $assetType = null;

                        if ($isVideo) {
                            $vid = Video::factory()->create();
                            $subData = ['id_video' => $vid->id_video];
                        } else {
                            $doc = Dokumen::factory()->create();
                            $subData = ['id_dokumen' => $doc->id_dokumen];
                        }

                        SubMateri::factory()->create(array_merge($subData, [
                            'id_materi' => $materi->id_materi,
                            'urutan' => $i
                        ]));
                    }
                }
            }
        }


        // ==========================================
        // 4. GENERATE TRANSACTIONS & ENROLLMENTS
        // ==========================================

        foreach ($allStudents as $student) {
            // Setiap student membeli 1-3 kelas secara acak
            $kelasDibeli = $allKelas->random(rand(1, 3));

            foreach ($kelasDibeli as $kelas) {
                // 1. Buat Header Transaksi
                $trx = Transaksi::factory()->create([
                    'id_user' => $student->id_user,
                    'total_harga' => $kelas->harga, // Asumsi beli satuan dulu biar gampang
                    'status' => 'paid', // Kita buat semua paid biar bisa isi progress
                ]);

                // 2. Buat Detail Transaksi
                TransaksiDetail::factory()->create([
                    'id_transaksi' => $trx->id_transaksi,
                    'id_kelas' => $kelas->id_kelas,
                    'harga_saat_beli' => $kelas->harga
                ]);

                // 3. Generate Progress Belajar (Karena sudah paid)
                // Ambil semua submateri di kelas ini
                $allSubMateri = SubMateri::join('materi', 'sub_materi.id_materi', '=', 'materi.id_materi')
                    ->where('materi.id_kelas', $kelas->id_kelas)
                    ->select('sub_materi.id_sub_materi')
                    ->get();

                foreach ($allSubMateri as $sub) {
                    // Randomize: 60% materi sudah selesai ditonton
                    ProgressSubMateri::factory()->create([
                        'id_user' => $student->id_user,
                        'id_kelas' => $kelas->id_kelas,
                        'id_sub_materi' => $sub->id_sub_materi,
                        'is_completed' => (bool)rand(0, 1)
                    ]);
                }

                // 4. Generate Review (Opsional, 40% chance user ngasih review)
                if (rand(1, 10) <= 4) {
                    Review::factory()->create([
                        'id_user' => $student->id_user,
                        'id_kelas' => $kelas->id_kelas
                    ]);
                }
            }
        }


        // ==========================================
        // 5. GENERATE REPORTS (Random Isu)
        // ==========================================
        Report::factory()->count(15)->create([
            'id_user' => $allStudents->random()->id_user,
            'id_kelas' => $allKelas->random()->id_kelas
        ]);
    }
}