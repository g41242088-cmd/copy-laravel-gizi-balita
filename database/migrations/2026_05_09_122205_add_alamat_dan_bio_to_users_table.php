<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom alamat_praktik dan bio
            $table->string('alamat_praktik')->nullable()->after('no_telepon');
            $table->text('bio')->nullable()->after('alamat_praktik');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['alamat_praktik', 'bio']);
        });
    }
};