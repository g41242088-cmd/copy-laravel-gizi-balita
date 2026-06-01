<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anak extends Model
{
    use HasFactory;

    protected $fillable = [
        'orangtua_id',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'berat_lahir',
        'panjang_lahir'
    ];

    // Anak dimiliki oleh 1 Orang Tua (User)
    public function orangtua()
    {
        return $this->belongsTo(User::class, 'orangtua_id');
    }

    // Anak bisa memiliki banyak Pengukuran
    public function pengukurans()
    {
        return $this->hasMany(Pengukuran::class, 'anak_id');
    }
}