<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jam_praktiks', function (Blueprint $table) {
            // Menambahkan kolom jam_mulai dan jam_selesai, kita buat nullable() agar tidak error jika ada data lama yang kosong
            $table->time('jam_mulai')->nullable()->after('is_active');
            $table->time('jam_selesai')->nullable()->after('jam_mulai');
        });
    }

    public function down(): void
    {
        Schema::table('jam_praktiks', function (Blueprint $table) {
            $table->dropColumn(['jam_mulai', 'jam_selesai']);
        });
    }
};