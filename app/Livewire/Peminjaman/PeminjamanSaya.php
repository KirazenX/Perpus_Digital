<?php

namespace App\Livewire\Peminjaman;

use App\Models\Peminjaman;
use App\Enums\StatusPeminjaman;
use Livewire\Component;
use Livewire\WithPagination;

class PeminjamanSaya extends Component
{
    use WithPagination;

    public string $statusFilter = '';

    public function render()
    {
        $query = Peminjaman::with('buku')
            ->where('UserID', auth()->id())
            ->when($this->statusFilter, fn($q) =>
                $q->where('StatusPeminjaman', $this->statusFilter)
            )
            ->latest();

        return view('livewire.peminjaman.peminjaman-saya', [
            'peminjamanList' => $query->paginate(10),
            'statusOptions' => StatusPeminjaman::cases(),
        ]);
    }
}