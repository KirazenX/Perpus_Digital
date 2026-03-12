<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Buku\BukuIndex;
use App\Livewire\Buku\BukuDetail;
use App\Livewire\Buku\BukuForm;
use App\Livewire\Peminjaman\PeminjamanForm;
use App\Livewire\Peminjaman\PeminjamanManage;
use App\Livewire\Peminjaman\PeminjamanSaya;
use App\Livewire\Admin\ManajemenPengguna;
use App\Livewire\Laporan\LaporanPeminjaman;

Route::get('/', function () {
    return redirect()->route('buku.index');
});

Route::get('/katalog', BukuIndex::class)->name('buku.index');
Route::get('/katalog/{buku}', BukuDetail::class)->name('buku.show');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        if ($role === 'administrator') return redirect()->route('admin.pengguna');
        if ($role === 'petugas') return redirect()->route('peminjaman.manage');
        return redirect()->route('peminjaman.saya');
    })->name('dashboard');

    Route::middleware(['role:peminjam'])->group(function () {
        Route::get('/pinjam/{buku}', PeminjamanForm::class)->name('peminjaman.create');
        Route::get('/peminjaman-saya', PeminjamanSaya::class)->name('peminjaman.saya');
    });

    Route::middleware(['role:administrator,petugas'])->group(function () {
        Route::get('/buku/tambah', BukuForm::class)->name('buku.create');
        Route::get('/buku/{buku}/edit', BukuForm::class)->name('buku.edit');
        Route::get('/kelola-peminjaman', PeminjamanManage::class)->name('peminjaman.manage');
        Route::get('/laporan', LaporanPeminjaman::class)->name('laporan.index');
    });

    Route::middleware(['role:administrator'])->group(function () {
        Route::get('/admin/pengguna', ManajemenPengguna::class)->name('admin.pengguna');
    });
});