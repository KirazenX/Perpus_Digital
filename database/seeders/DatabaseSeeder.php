<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Buku;
use App\Models\KategoriBuku;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Azriel',
            'email' => 'azriel@example.com',
            'password' => Hash::make('azriel123'),
            'role' => 'administrator',
            'NamaLengkap' => 'Azriel Administrator',
            'Alamat' => ' Keben',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Dika',
            'email' => 'dika@example.com',
            'password' => Hash::make('dika1234'),
            'role' => 'petugas',
            'NamaLengkap' => 'Dika Petugas',
            'Alamat' => 'Mulyorejo',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Farel',
            'email' => 'farel@example.com',
            'password' => Hash::make('farel123'),
            'role' => 'peminjam',
            'NamaLengkap' => 'Farel Trio Saputra',
            'Alamat' => 'Kebonagung',
            'email_verified_at' => now(),
        ]);

        $kategori = [
            ['NamaKategori' => 'Fiksi'],
            ['NamaKategori' => 'Non-Fiksi'],
            ['NamaKategori' => 'Sains & Teknologi'],
            ['NamaKategori' => 'Sejarah'],
            ['NamaKategori' => 'Pendidikan'],
            ['NamaKategori' => 'Agama'],
            ['NamaKategori' => 'Kesehatan'],
            ['NamaKategori' => 'Biografi'],
        ];

        foreach ($kategori as $k) {
            KategoriBuku::create($k);
        }

        $buku = [
            [
                'Judul' => 'Bumi Manusia',
                'Penulis' => 'Pramoedya Ananta Toer',
                'Penerbit' => 'Hasta Mitra',
                'TahunTerbit' => 1980,
                'ISBN' => '978-979-889-163-4',
                'Deskripsi' => 'Novel sejarah tentang kehidupan di masa kolonial Belanda.',
                'StokTotal' => 3,
                'StokTersedia' => 3,
            ],
            [
                'Judul' => 'Laskar Pelangi',
                'Penulis' => 'Andrea Hirata',
                'Penerbit' => 'Bentang Pustaka',
                'TahunTerbit' => 2005,
                'ISBN' => '978-979-1227-47-9',
                'Deskripsi' => 'Novel tentang perjuangan anak-anak Belitung untuk mendapatkan pendidikan.',
                'StokTotal' => 5,
                'StokTersedia' => 5,
            ],
            [
                'Judul' => 'Clean Code',
                'Penulis' => 'Robert C. Martin',
                'Penerbit' => 'Prentice Hall',
                'TahunTerbit' => 2008,
                'ISBN' => '978-0132350884',
                'Deskripsi' => 'Panduan menulis kode program yang bersih dan maintainable.',
                'StokTotal' => 2,
                'StokTersedia' => 2,
            ],
        ];

        foreach ($buku as $b) {
            $newBuku = Buku::create($b);
            $newBuku->kategori()->attach(
                KategoriBuku::inRandomOrder()->take(2)->pluck('KategoriID')
            );
        }
    }
}