<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\StatusPeminjaman;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    protected $primaryKey = 'PeminjamanID';

    protected $fillable = [
        'UserID',
        'BukuID',
        'TanggalPeminjaman',
        'TanggalPengembalian',
        'TanggalDikembalikan',
        'StatusPeminjaman',
        'Catatan',
    ];

    protected $casts = [
        'TanggalPeminjaman' => 'date',
        'TanggalPengembalian' => 'date',
        'TanggalDikembalikan' => 'date',
        'StatusPeminjaman' => StatusPeminjaman::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'BukuID');
    }

    public function isTerlambat(): bool
    {
        if ($this->StatusPeminjaman === StatusPeminjaman::DIPINJAM) {
            return now()->gt($this->TanggalPengembalian);
        }
        return false;
    }
}