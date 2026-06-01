<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anaks', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users (siapa orang tuanya)
            $table->foreignId('orangtua_id')->constrained('users')->cascadeOnDelete(); 
            
            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir');
            $table->float('berat_lahir')->nullable(); // Dalam kg
            $table->float('panjang_lahir')->nullable(); // Dalam cm
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anaks');
    }
};