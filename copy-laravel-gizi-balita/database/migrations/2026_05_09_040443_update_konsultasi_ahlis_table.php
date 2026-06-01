<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('konsultasi_ahlis', function (Blueprint $table) {
            // 1. Tambahkan kolom kategori untuk membedakan Dokter/Ahli Gizi
            $table->enum('kategori_ahli', ['dokter', 'ahligizi'])->after('ahli_id')->nullable();

            // 2. Tambahkan kolom anak_id agar bisa relasi dengan tabel anaks (untuk halaman Stepper)
            // Cek di tabel anda, jika belum ada anak_id, gunakan baris ini:
            if (!Schema::hasColumn('konsultasi_ahlis', 'anak_id')) {
                $table->foreignId('anak_id')->nullable()->after('orangtua_id')->constrained('anaks')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('konsultasi_ahlis', function (Blueprint $table) {
            $table->dropColumn(['kategori_ahli']);
            // Jangan hapus anak_id di down jika sebelumnya sudah ada
        });
    }
};