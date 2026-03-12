<?php

namespace App\Enums;

enum StatusPeminjaman: string
{
    case MENUNGGU = 'menunggu';
    case DIPINJAM = 'dipinjam';
    case DIKEMBALIKAN = 'dikembalikan';
    case DITOLAK = 'ditolak';
    case TERLAMBAT = 'terlambat';

    public function label(): string
    {
        return match($this) {
            self::MENUNGGU => 'Menunggu Persetujuan',
            self::DIPINJAM => 'Sedang Dipinjam',
            self::DIKEMBALIKAN => 'Sudah Dikembalikan',
            self::DITOLAK => 'Ditolak',
            self::TERLAMBAT => 'Terlambat',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::MENUNGGU => 'warning',
            self::DIPINJAM => 'info',
            self::DIKEMBALIKAN => 'success',
            self::DITOLAK => 'danger',
            self::TERLAMBAT => 'danger',
        };
    }

    public function badge(): string
    {
        return match($this) {
            self::MENUNGGU => 'bg-yellow-100 text-yellow-800',
            self::DIPINJAM => 'bg-blue-100 text-blue-800',
            self::DIKEMBALIKAN => 'bg-green-100 text-green-800',
            self::DITOLAK => 'bg-red-100 text-red-800',
            self::TERLAMBAT => 'bg-red-100 text-red-800',
        };
    }
}