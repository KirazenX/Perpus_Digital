<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategoribuku', function (Blueprint $table) {
            $table->id('KategoriID');
            $table->string('NamaKategori', 255);
            $table->timestamps();
        });

        Schema::create('kategoribuku_relasi', function (Blueprint $table) {
            $table->id('KategoriBukuID');
            $table->unsignedBigInteger('BukuID');
            $table->unsignedBigInteger('KategoriID');
            $table->timestamps();

            $table->foreign('BukuID')->references('BukuID')->on('buku')->onDelete('cascade');
            $table->foreign('KategoriID')->references('KategoriID')->on('kategoribuku')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategoribuku_relasi');
        Schema::dropIfExists('kategoribuku');
    }
};