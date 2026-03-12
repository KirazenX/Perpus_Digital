<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UlasanBuku extends Model
{
    use HasFactory;

    protected $table = 'ulasanbuku';
    protected $primaryKey = 'UlasanID';

    protected $fillable = ['UserID', 'BukuID', 'Ulasan', 'Rating'];

    protected $casts = [
        'Rating' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'BukuID');
    }
}