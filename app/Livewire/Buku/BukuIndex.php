<?php

namespace App\Livewire\Buku;

use App\Models\Buku;
use App\Models\KategoriBuku;
use Livewire\Component;
use Livewire\WithPagination;

class BukuIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $kategoriFilter = '';
    public string $sortBy = 'Judul';
    public string $sortDir = 'asc';

    protected $queryString = ['search', 'kategoriFilter'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingKategoriFilter(): void
    {
        $this->resetPage();
    }

    public function sortColumn(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }
    }

    public function render()
    {
        $query = Buku::with('kategori')
            ->when($this->search, fn($q) =>
                $q->where('Judul', 'like', "%{$this->search}%")
                  ->orWhere('Penulis', 'like', "%{$this->search}%")
            )
            ->when($this->kategoriFilter, fn($q) =>
                $q->whereHas('kategori', fn($kq) =>
                    $kq->where('kategoribuku_relasi.KategoriID', $this->kategoriFilter)
                )
            )
            ->orderBy($this->sortBy, $this->sortDir);

        return view('livewire.buku.buku-index', [
            'bukuList' => $query->paginate(12),
            'kategoriList' => KategoriBuku::orderBy('NamaKategori')->get(),
        ]);
    }
}
