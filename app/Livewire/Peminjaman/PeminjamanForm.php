<?php

namespace App\Livewire\Peminjaman;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Enums\StatusPeminjaman;
use Livewire\Component;

class PeminjamanForm extends Component
{
    public Buku $buku;
    public string $TanggalPeminjaman = '';
    public string $TanggalPengembalian = '';

    protected $rules = [
        'TanggalPeminjaman' => 'required|date|after_or_equal:today',
        'TanggalPengembalian' => 'required|date|after:TanggalPeminjaman',
    ];

    protected $messages = [
        'TanggalPeminjaman.required' => 'Tanggal peminjaman wajib diisi.',
        'TanggalPeminjaman.after_or_equal' => 'Tanggal peminjaman tidak boleh sebelum hari ini.',
        'TanggalPengembalian.required' => 'Tanggal pengembalian wajib diisi.',
        'TanggalPengembalian.after' => 'Tanggal pengembalian harus setelah tanggal peminjaman.',
    ];

    public function mount(Buku $buku): void
    {
        $this->buku = $buku;
        $this->TanggalPeminjaman = now()->format('Y-m-d');
        $this->TanggalPengembalian = now()->addDays(7)->format('Y-m-d');
    }

    public function submit(): void
    {
        $this->validate();

        if (!$this->buku->isAvailable()) {
            session()->flash('error', 'Maaf, buku ini sedang tidak tersedia.');
            return;
        }
        
        $existing = Peminjaman::where('UserID', auth()->id())
            ->where('BukuID', $this->buku->BukuID)
            ->whereIn('StatusPeminjaman', [StatusPeminjaman::MENUNGGU, StatusPeminjaman::DIPINJAM])
            ->exists();

        if ($existing) {
            session()->flash('error', 'Anda sudah memiliki peminjaman aktif untuk buku ini.');
            return;
        }

        Peminjaman::create([
            'UserID' => auth()->id(),
            'BukuID' => $this->buku->BukuID,
            'TanggalPeminjaman' => $this->TanggalPeminjaman,
            'TanggalPengembalian' => $this->TanggalPengembalian,
            'StatusPeminjaman' => StatusPeminjaman::MENUNGGU,
        ]);

        session()->flash('success', 'Permintaan peminjaman berhasil dikirim. Menunggu persetujuan petugas.');
        $this->redirectRoute('peminjaman.saya');
    }

    public function render()
    {
        return view('livewire.peminjaman.peminjaman-form');
    }
}