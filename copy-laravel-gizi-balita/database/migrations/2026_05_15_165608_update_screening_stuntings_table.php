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
        Schema::table('screening_stuntings', function (Blueprint $table) {

            // Antropometri tambahan
            $table->decimal('lingkar_kepala', 5, 2)
                  ->nullable()
                  ->after('tinggi_badan');

            $table->decimal('lila', 5, 2)
                  ->nullable()
                  ->after('lingkar_kepala');

            // Riwayat kesehatan
            $table->boolean('asi_eksklusif')
                  ->nullable()
                  ->after('rekomendasi');

            $table->boolean('imunisasi_lengkap')->nullable();

            $table->boolean('riwayat_prematur')->nullable();

            $table->decimal('berat_lahir', 5, 2)
                  ->nullable();

            $table->boolean('riwayat_diare_berulang')->nullable();

            $table->boolean('riwayat_rawat_inap')->nullable();

            $table->text('riwayat_penyakit')->nullable();

            // Pola makan dan sanitasi
            $table->integer('frekuensi_makan')
                  ->nullable();

            $table->boolean('mpasi_sesuai_usia')->nullable();

            $table->boolean('konsumsi_protein')->nullable();

            $table->boolean('konsumsi_suplemen')->nullable();

            $table->boolean('akses_air_bersih')->nullable();

            $table->boolean('kebiasaan_cuci_tangan')->nullable();

            $table->string('kondisi_sanitasi')
                  ->nullable();

            // Jadwal kontrol
            $table->date('jadwal_kontrol_berikutnya')
                  ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('screening_stuntings', function (Blueprint $table) {

            $table->dropColumn([
                'lingkar_kepala',
                'lila',
                'asi_eksklusif',
                'imunisasi_lengkap',
                'riwayat_prematur',
                'berat_lahir',
                'riwayat_diare_berulang',
                'riwayat_rawat_inap',
                'riwayat_penyakit',
                'frekuensi_makan',
                'mpasi_sesuai_usia',
                'konsumsi_protein',
                'konsumsi_suplemen',
                'akses_air_bersih',
                'kebiasaan_cuci_tangan',
                'kondisi_sanitasi',
                'jadwal_kontrol_berikutnya'
            ]);
        });
    }
};