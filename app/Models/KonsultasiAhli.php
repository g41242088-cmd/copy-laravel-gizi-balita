<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KonsultasiAhli extends Model
{
    use HasFactory;

    protected $fillable = [
        'orangtua_id',
        'anak_id',
        'ahli_id',
        'kategori_ahli', // Untuk membedakan 'dokter' atau 'ahligizi'
        'nama_ortu',
        'nama_anak',
        'umur_anak',
        'jenis_kelamin_anak',
        'no_wa',
        'tanggal_jadwal',
        'jam_jadwal',
        'catatan',
        'status',
        'subjective',
        'suhu',
        'objective',
        'assessment',
        'plan',
        'vaksin',
    ];

    // Relasi ke User (Orang Tua)
    public function orangtua()
    {
        return $this->belongsTo(User::class, 'orangtua_id');
    }

    // Relasi ke User (Dokter/Ahli Gizi)
    public function ahli()
    {
        return $this->belongsTo(User::class, 'ahli_id');
    }

    // Relasi ke Anak (Hanya terisi jika booking lewat Stepper Dokter)
    public function anak()
    {
        return $this->belongsTo(Anak::class, 'anak_id');
    }
}
