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
        Schema::table('jam_praktiks', function (Blueprint $table) {
            // Menambahkan kolom is_active setelah kolom hari
            // default(false) artinya secara bawaan jadwalnya bernilai 0 (tutup)
            $table->boolean('is_active')->default(false)->after('hari');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jam_praktiks', function (Blueprint $table) {
            // Menghapus kolom jika migrasi di-rollback
            $table->dropColumn('is_active');
        });
    }
};