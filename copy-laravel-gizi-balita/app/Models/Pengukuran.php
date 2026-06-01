<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengukuran extends Model
{
    use HasFactory;

    protected $fillable = [
        'anak_id',
        'tanggal_ukur',
        'umur_bulan',
        'berat_badan',
        'tinggi_badan',
        'status_gizi'
    ];

    // Pengukuran dimiliki oleh 1 Anak
    public function anak()
    {
        return $this->belongsTo(Anak::class);
    }
}