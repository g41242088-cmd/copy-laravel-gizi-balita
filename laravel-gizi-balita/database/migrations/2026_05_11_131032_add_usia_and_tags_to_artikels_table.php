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
    Schema::table('artikels', function (Blueprint $table) {
        $table->string('rentang_usia')->nullable()->after('kategori');
        $table->string('tags')->nullable()->after('rentang_usia'); // Disimpan pisah koma: "ASI, MPASI, Bayi"
    });
}

public function down(): void
{
    Schema::table('artikels', function (Blueprint $table) {
        $table->dropColumn(['rentang_usia', 'tags']);
    });
}
};
