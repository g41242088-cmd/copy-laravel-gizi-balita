<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fungsi up() dijalankan saat kita melakukan migrate
        Schema::table('konsultasi_ahlis', function (Blueprint $table) {
            // Menambahkan 2 kolom baru tepat setelah kolom 'no_wa'
            $table->date('tanggal_jadwal')->after('no_wa')->nullable();
            $table->time('jam_jadwal')->after('tanggal_jadwal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Fungsi down() dijalankan jika kita melakukan rollback (membatalkan migrate)
        Schema::table('konsultasi_ahlis', function (Blueprint $table) {
            // Menghapus kolom jika dibatalkan
            $table->dropColumn(['tanggal_jadwal', 'jam_jadwal']);
        });
    }
};