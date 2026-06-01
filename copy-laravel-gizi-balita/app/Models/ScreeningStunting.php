<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScreeningStunting extends Model
{
    protected $fillable = [
        'anak_id',
        'orangtua_id',

        // Data antropometri
        'berat_badan',
        'tinggi_badan',
        'lingkar_kepala',
        'lila',
        'usia_bulan',

        // Hasil screening
        'zscore',
        'status',
        'rekomendasi',

        // Riwayat kesehatan
        'asi_eksklusif',
        'imunisasi_lengkap',
        'riwayat_prematur',
        'berat_lahir',
        'riwayat_diare_berulang',
        'riwayat_rawat_inap',
        'riwayat_penyakit',

        // Pola makan dan sanitasi
        'frekuensi_makan',
        'mpasi_sesuai_usia',
        'konsumsi_protein',
        'konsumsi_suplemen',
        'akses_air_bersih',
        'kebiasaan_cuci_tangan',
        'kondisi_sanitasi',

        // Jadwal kontrol
        'jadwal_kontrol_berikutnya',
    ];

    /**
     * Relasi ke data anak
     */
    public function anak()
    {
        return $this->belongsTo(Anak::class);
    }

    /**
     * Relasi ke orang tua
     */
    public function orangtua()
    {
        return $this->belongsTo(User::class, 'orangtua_id');
    }
}