<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';
    protected $primaryKey = 'BukuID';

    protected $fillable = [
        'Judul',
        'Penulis',
        'Penerbit',
        'TahunTerbit',
        'ISBN',
        'Deskripsi',
        'CoverImage',
        'StokTotal',
        'StokTersedia',
    ];

    public function kategori()
    {
        return $this->belongsToMany(KategoriBuku::class, 'kategoribuku_relasi', 'BukuID', 'KategoriID')
            ->withTimestamps();
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'BukuID');
    }

    public function ulasan()
    {
        return $this->hasMany(UlasanBuku::class, 'BukuID');
    }

    public function koleksiPribadi()
    {
        return $this->hasMany(KoleksiPribadi::class, 'BukuID');
    }

    public function isAvailable(): bool
    {
        return $this->StokTersedia > 0;
    }

    public function averageRating(): float
    {
        return round($this->ulasan()->avg('Rating') ?? 0, 1);
    }
}