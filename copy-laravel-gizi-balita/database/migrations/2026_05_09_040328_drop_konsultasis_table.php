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
    Schema::dropIfExists('konsultasis');
}

public function down(): void
{
    // Opsional: Jika ingin rollback, buat kembali tabelnya (tapi biasanya biarkan kosong jika yakin ingin hapus)
}
};
