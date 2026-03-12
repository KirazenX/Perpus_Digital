<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id('PeminjamanID');
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('BukuID');
            $table->date('TanggalPeminjaman');
            $table->date('TanggalPengembalian');
            $table->date('TanggalDikembalikan')->nullable();
            $table->enum('StatusPeminjaman', ['menunggu', 'dipinjam', 'dikembalikan', 'ditolak', 'terlambat'])->default('menunggu');
            $table->text('Catatan')->nullable();
            $table->timestamps();

            $table->foreign('UserID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('BukuID')->references('BukuID')->on('buku')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};