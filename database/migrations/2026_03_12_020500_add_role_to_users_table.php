<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['administrator', 'petugas', 'peminjam'])->default('peminjam')->after('email');
            $table->string('NamaLengkap', 255)->nullable()->after('role');
            $table->text('Alamat')->nullable()->after('NamaLengkap');
            $table->boolean('is_active')->default(true)->after('Alamat');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'NamaLengkap', 'Alamat', 'is_active']);
        });
    }
};