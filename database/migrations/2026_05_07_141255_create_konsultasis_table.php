<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konsultasis', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel users sebagai Orang Tua
            $table->foreignId('orangtua_id')->constrained('users')->cascadeOnDelete();
            
            // Relasi ke tabel anaks sebagai pasien
            $table->foreignId('anak_id')->constrained('anaks')->cascadeOnDelete();
            
            // Relasi ke tabel users sebagai Dokter/Ahli Gizi
            $table->foreignId('dokter_id')->constrained('users')->cascadeOnDelete();
            
            // Detail Jadwal
            $table->date('tanggal_booking');
            $table->time('jam_booking');
            $table->string('no_wa');
            $table->text('catatan')->nullable(); 
            
            // Status Booking
            $table->enum('status', ['menunggu', 'dikonfirmasi', 'selesai', 'batal'])->default('menunggu');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konsultasis');
    }
};