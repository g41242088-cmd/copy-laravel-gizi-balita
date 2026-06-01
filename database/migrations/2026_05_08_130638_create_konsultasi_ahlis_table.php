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
        Schema::create('konsultasi_ahlis', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel users (sebagai orang tua)
            $table->foreignId('orangtua_id')->constrained('users')->onDelete('cascade');
            // Menghubungkan ke tabel users (sebagai dokter/ahli gizi)
            $table->foreignId('ahli_id')->constrained('users')->onDelete('cascade');
            
            $table->string('nama_ortu');
            $table->string('nama_anak');
            $table->string('umur_anak');
            $table->enum('jenis_kelamin_anak', ['L', 'P']);
            $table->string('no_wa');
            $table->text('catatan')->nullable();
            
            // Status pengajuan
            $table->enum('status', ['menunggu', 'disetujui', 'selesai', 'batal'])->default('menunggu');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konsultasi_ahlis');
    }
};