<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id('BukuID');
            $table->string('Judul', 255);
            $table->string('Penulis', 255);
            $table->string('Penerbit', 255);
            $table->integer('TahunTerbit');
            $table->string('ISBN', 50)->nullable()->unique();
            $table->text('Deskripsi')->nullable();
            $table->string('CoverImage')->nullable();
            $table->integer('StokTotal')->default(1);
            $table->integer('StokTersedia')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku');
    }
};