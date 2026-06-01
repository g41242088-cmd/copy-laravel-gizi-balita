<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Dokter',
            'email' => 'dokter@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'dokter'
        ]);

        User::create([
            'name' => 'Ahli Gizi',
            'email' => 'gizi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'ahligizi'
        ]);

        User::create([
            'name' => 'Orang Tua',
            'email' => 'orangtua@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'orangtua'
        ]);
    }
}