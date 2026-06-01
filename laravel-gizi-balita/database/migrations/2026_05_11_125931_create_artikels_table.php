<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('artikels', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->longText('konten')->nullable(); // Menggunakan longText agar muat artikel panjang
            $table->string('kategori'); // Nutrisi & Gizi, Resep MPASI, Pola Asuh
            $table->string('penulis')->nullable();
            $table->enum('status', ['terbit', 'draft'])->default('draft');
            $table->integer('views')->default(0); // Kolom untuk menghitung yang baca
            $table->timestamps();
        });
    }

    /**
     * Kembalikan migration (rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('artikels');
    }
};