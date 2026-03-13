<?php

namespace App\Livewire\Peminjaman;

use App\Models\KoleksiPribadi;
use Livewire\Component;
use Livewire\WithPagination;

class KoleksiSaya extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function hapusDariKoleksi(int $bukuId): void
    {
        KoleksiPribadi::where('UserID', auth()->id())
            ->where('BukuID', $bukuId)
            ->delete();

        session()->flash('success', 'Buku berhasil dihapus dari koleksi.');
    }

    public function render()
    {
        $koleksiList = KoleksiPribadi::with('buku.kategori')
            ->where('UserID', auth()->id())
            ->when($this->search, fn ($q) => $q->whereHas('buku', fn ($bq) => $bq->where('Judul', 'like', "%{$this->search}%")
                ->orWhere('Penulis', 'like', "%{$this->search}%")
            )
            )
            ->latest()
            ->paginate(12);

        return view('livewire.peminjaman.koleksi-saya', compact('koleksiList'));
    }
}
