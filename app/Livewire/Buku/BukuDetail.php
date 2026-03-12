<?php

namespace App\Livewire\Buku;

use App\Models\Buku;
use App\Models\KoleksiPribadi;
use App\Models\UlasanBuku;
use Livewire\Component;

class BukuDetail extends Component
{
    public Buku $buku;
    public string $ulasan = '';
    public int $rating = 5;
    public bool $isInKoleksi = false;

    public function mount(Buku $buku): void
    {
        $this->buku = $buku->load(['kategori', 'ulasan.user']);
        if (auth()->check()) {
            $this->isInKoleksi = KoleksiPribadi::where('UserID', auth()->id())
                ->where('BukuID', $buku->BukuID)->exists();
        }
    }

    public function toggleKoleksi(): void
    {
        if (!auth()->check()) {
            $this->redirectRoute('login');
            return;
        }

        $existing = KoleksiPribadi::where('UserID', auth()->id())
            ->where('BukuID', $this->buku->BukuID)->first();

        if ($existing) {
            $existing->delete();
            $this->isInKoleksi = false;
            session()->flash('info', 'Buku dihapus dari koleksi pribadi.');
        } else {
            KoleksiPribadi::create([
                'UserID' => auth()->id(),
                'BukuID' => $this->buku->BukuID,
            ]);
            $this->isInKoleksi = true;
            session()->flash('success', 'Buku ditambahkan ke koleksi pribadi.');
        }
    }

    public function submitUlasan(): void
    {
        if (!auth()->check()) {
            $this->redirectRoute('login');
            return;
        }

        $this->validate([
            'ulasan' => 'required|string|min:10|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        UlasanBuku::updateOrCreate(
            ['UserID' => auth()->id(), 'BukuID' => $this->buku->BukuID],
            ['Ulasan' => $this->ulasan, 'Rating' => $this->rating]
        );

        $this->ulasan = '';
        $this->rating = 5;
        $this->buku->refresh();
        $this->buku->load('ulasan.user');
        session()->flash('success', 'Ulasan berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.buku.buku-detail');
    }
}