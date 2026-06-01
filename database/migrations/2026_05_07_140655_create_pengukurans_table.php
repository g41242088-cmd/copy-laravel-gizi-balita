<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengukurans', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel anak (pengukuran ini milik anak siapa)
            $table->foreignId('anak_id')->constrained('anaks')->cascadeOnDelete();
            
            $table->date('tanggal_ukur');
            $table->integer('umur_bulan'); // Umur anak saat diukur (dalam bulan)
            $table->float('berat_badan'); // Dalam kg
            $table->float('tinggi_badan'); // Dalam cm
            
            // Hasil kalkulasi sistem
            $table->enum('status_gizi', ['normal', 'kurang_gizi', 'stunting', 'obesitas'])->default('normal');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengukurans');
    }
};