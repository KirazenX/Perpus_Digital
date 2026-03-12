<?php

namespace App\Models;

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
        'NamaLengkap',
        'Alamat',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function isAdministrator(): bool
    {
        return $this->role === 'administrator';
    }

    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    public function isPeminjam(): bool
    {
        return $this->role === 'peminjam';
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['administrator', 'petugas']);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'UserID');
    }

    public function koleksiPribadi()
    {
        return $this->hasMany(KoleksiPribadi::class, 'UserID');
    }

    public function ulasanBuku()
    {
        return $this->hasMany(UlasanBuku::class, 'UserID');
    }

    public function bukuKoleksi()
    {
        return $this->belongsToMany(Buku::class, 'koleksipribadi', 'UserID', 'BukuID')
            ->withTimestamps();
    }
}