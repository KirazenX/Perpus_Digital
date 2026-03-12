<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('koleksipribadi', function (Blueprint $table) {
            $table->id('KoleksiID');
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('BukuID');
            $table->timestamps();

            $table->foreign('UserID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('BukuID')->references('BukuID')->on('buku')->onDelete('cascade');
            $table->unique(['UserID', 'BukuID']);
        });

        Schema::create('ulasanbuku', function (Blueprint $table) {
            $table->id('UlasanID');
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('BukuID');
            $table->text('Ulasan');
            $table->integer('Rating')->default(5);
            $table->timestamps();

            $table->foreign('UserID')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('BukuID')->references('BukuID')->on('buku')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasanbuku');
        Schema::dropIfExists('koleksipribadi');
    }
};