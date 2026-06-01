<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Anak;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Akun Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'), // Password login: password
            'role' => 'admin',
        ]);

        // 2. Buat Akun Tenaga Medis (Ahli Gizi)
        $dokter1 = User::create([
            'name' => 'Dr. AzzaNafila, S.Gz',
            'email' => 'azza@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'dokter',
            'spesialisasi' => 'Gizi Klinis & Tumbuh Kembang'
        ]);

        $dokter2 = User::create([
            'name' => 'Ns. Fila Nafila, S.Gz',
            'email' => 'fila@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'ahligizi',
            'spesialisasi' => 'Ahli Gizi Bayi & Laktasi'
        ]);

        // 3. Buat Akun Orang Tua (Akun Anda)
        $ortu = User::create([
            'name' => 'Aditya', // Menggunakan nama Anda
            'email' => 'aditya@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'orangtua',
        ]);

        // 4. Buat Data Anak untuk Akun Aditya
        Anak::create([
            'orangtua_id' => $ortu->id,
            'nama' => 'dharma',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => now()->subMonths(2), // Umur 2 bulan
            'berat_lahir' => 3.2,
            'panjang_lahir' => 50,
        ]);

        Anak::create([
            'orangtua_id' => $ortu->id,
            'nama' => 'putra',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => now()->subYears(3), // Umur 3 tahun
            'berat_lahir' => 2.9,
            'panjang_lahir' => 48,
        ]);
    }
}