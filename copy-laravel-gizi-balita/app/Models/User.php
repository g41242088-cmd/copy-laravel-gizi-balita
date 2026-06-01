<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role', 
        'spesialisasi', 
        'no_telepon', 
        'kota', 
        'jenis_kelamin', 
        'tanggal_lahir',
        'no_sip',
        'alamat_praktik',
        'bio'
    ];

    protected $hidden = [
        'password', 
        'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function anaks()
    {
        return $this->hasMany(Anak::class, 'orangtua_id');
    }
    
    public function bookingSebagaiOrtu()
    {
        return $this->hasMany(KonsultasiAhli::class, 'orangtua_id');
    }

    public function bookingSebagaiDokter()
    {
        return $this->hasMany(KonsultasiAhli::class, 'ahli_id');
    }
    
    public function jamPraktiks()
    {
        return $this->hasMany(JamPraktik::class, 'ahli_id');
    }
}