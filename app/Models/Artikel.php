<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    use HasFactory;

    // Daftarkan semua kolom yang boleh diisi, TERMASUK GAMBAR
    protected $fillable = [
        'judul',
        'gambar', // 👈 INI DIA YANG KURANG SEBELUMNYA!
        'kategori',
        'rentang_usia',
        'tags',
        'status',
        'konten',
        'penulis',
        'views',
    ];
}