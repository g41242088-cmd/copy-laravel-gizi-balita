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
    Schema::table('konsultasi_ahlis', function (Blueprint $table) {
        $table->text('subjective')->nullable();
        $table->string('suhu')->nullable();
        $table->string('objective')->nullable();
        $table->string('assessment')->nullable();
        $table->text('plan')->nullable();
        $table->string('vaksin')->nullable();
    });
}

public function down(): void
{
    Schema::table('konsultasi_ahlis', function (Blueprint $table) {
        $table->dropColumn(['subjective', 'suhu', 'objective', 'assessment', 'plan', 'vaksin']);
    });
}
};
