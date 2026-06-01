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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom baru (nullable agar akun lama tidak error karena kosong)
            $table->string('no_telepon', 20)->nullable()->after('email');
            $table->string('kota', 100)->nullable()->after('no_telepon');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('kota');
            $table->date('tanggal_lahir')->nullable()->after('jenis_kelamin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn(['no_telepon', 'kota', 'jenis_kelamin', 'tanggal_lahir']);
        });
    }
};