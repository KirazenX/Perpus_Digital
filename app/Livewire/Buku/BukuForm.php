<?php

namespace App\Livewire\Buku;

use App\Models\Buku;
use App\Models\KategoriBuku;
use Livewire\Component;
use Livewire\WithFileUploads;

class BukuForm extends Component
{
    use WithFileUploads;

    public ?Buku $buku = null;
    public bool $isEdit = false;

    public string $Judul = '';
    public string $Penulis = '';
    public string $Penerbit = '';
    public string $TahunTerbit = '';
    public string $ISBN = '';
    public string $Deskripsi = '';
    public $CoverImage = null;
    public int $StokTotal = 1;
    public array $selectedKategori = [];

    protected function rules(): array
    {
        $isbnRule = $this->isEdit
            ? 'nullable|string|unique:buku,ISBN,' . $this->buku?->BukuID . ',BukuID'
            : 'nullable|string|unique:buku,ISBN';

        return [
            'Judul' => 'required|string|max:255',
            'Penulis' => 'required|string|max:255',
            'Penerbit' => 'required|string|max:255',
            'TahunTerbit' => 'required|integer|min:1000|max:' . (date('Y') + 1),
            'ISBN' => $isbnRule,
            'Deskripsi' => 'nullable|string',
            'CoverImage' => 'nullable|image|max:2048',
            'StokTotal' => 'required|integer|min:1',
            'selectedKategori' => 'nullable|array',
        ];
    }

    protected $messages = [
        'Judul.required' => 'Judul buku wajib diisi.',
        'Penulis.required' => 'Nama penulis wajib diisi.',
        'Penerbit.required' => 'Nama penerbit wajib diisi.',
        'TahunTerbit.required' => 'Tahun terbit wajib diisi.',
        'StokTotal.required' => 'Stok buku wajib diisi.',
        'ISBN.unique' => 'ISBN ini sudah terdaftar.',
        'CoverImage.image' => 'File harus berupa gambar.',
        'CoverImage.max' => 'Ukuran gambar maksimal 2MB.',
    ];

    public function mount(?Buku $buku = null): void
    {
        if ($buku && $buku->exists) {
            $this->buku = $buku;
            $this->isEdit = true;
            $this->Judul = $buku->Judul;
            $this->Penulis = $buku->Penulis;
            $this->Penerbit = $buku->Penerbit;
            $this->TahunTerbit = $buku->TahunTerbit;
            $this->ISBN = $buku->ISBN ?? '';
            $this->Deskripsi = $buku->Deskripsi ?? '';
            $this->StokTotal = $buku->StokTotal;
            $this->selectedKategori = $buku->kategori->pluck('KategoriID')->toArray();
        }
    }

    public function save(): void
    {
        $this->validate();

        $coverPath = null;
        if ($this->CoverImage) {
            $coverPath = $this->CoverImage->store('covers', 'public');
        }

        $data = [
            'Judul' => $this->Judul,
            'Penulis' => $this->Penulis,
            'Penerbit' => $this->Penerbit,
            'TahunTerbit' => $this->TahunTerbit,
            'ISBN' => $this->ISBN ?: null,
            'Deskripsi' => $this->Deskripsi,
            'StokTotal' => $this->StokTotal,
        ];

        if ($coverPath) {
            $data['CoverImage'] = $coverPath;
        }

        if ($this->isEdit) {
            $selisih = $this->StokTotal - $this->buku->StokTotal;
            $data['StokTersedia'] = max(0, $this->buku->StokTersedia + $selisih);
            $this->buku->update($data);
            $this->buku->kategori()->sync($this->selectedKategori);
            session()->flash('success', 'Data buku berhasil diperbarui.');
        } else {
            $data['StokTersedia'] = $this->StokTotal;
            $buku = Buku::create($data);
            $buku->kategori()->sync($this->selectedKategori);
            session()->flash('success', 'Buku berhasil ditambahkan.');
        }

        $this->redirectRoute('buku.index');
    }

    public function render()
    {
        return view('livewire.buku.buku-form', [
            'kategoriList' => KategoriBuku::orderBy('NamaKategori')->get(),
        ]);
    }
}