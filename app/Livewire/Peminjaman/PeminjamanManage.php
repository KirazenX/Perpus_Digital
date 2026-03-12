<?php

namespace App\Livewire\Peminjaman;

use App\Models\Peminjaman;
use App\Enums\StatusPeminjaman;
use Livewire\Component;
use Livewire\WithPagination;

class PeminjamanManage extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public ?int $confirmingApprove = null;
    public ?int $confirmingReject = null;
    public ?int $confirmingReturn = null;

    protected $queryString = ['statusFilter'];

    public function updatingSearch(): void { $this->resetPage(); }

    public function approve(int $id): void
    {
        $peminjaman = Peminjaman::findOrFail($id);
        if ($peminjaman->StatusPeminjaman !== StatusPeminjaman::MENUNGGU) return;

        if (!$peminjaman->buku->isAvailable()) {
            session()->flash('error', 'Stok buku tidak tersedia.');
            $this->confirmingApprove = null;
            return;
        }

        $peminjaman->update(['StatusPeminjaman' => StatusPeminjaman::DIPINJAM]);
        $peminjaman->buku->decrement('StokTersedia');
        $this->confirmingApprove = null;
        session()->flash('success', 'Peminjaman berhasil disetujui.');
    }

    public function reject(int $id): void
    {
        $peminjaman = Peminjaman::findOrFail($id);
        if ($peminjaman->StatusPeminjaman !== StatusPeminjaman::MENUNGGU) return;

        $peminjaman->update(['StatusPeminjaman' => StatusPeminjaman::DITOLAK]);
        $this->confirmingReject = null;
        session()->flash('success', 'Peminjaman telah ditolak.');
    }

    public function returnBook(int $id): void
    {
        $peminjaman = Peminjaman::findOrFail($id);
        if (!in_array($peminjaman->StatusPeminjaman, [StatusPeminjaman::DIPINJAM, StatusPeminjaman::TERLAMBAT])) return;

        $peminjaman->update([
            'StatusPeminjaman' => StatusPeminjaman::DIKEMBALIKAN,
            'TanggalDikembalikan' => now()->format('Y-m-d'),
        ]);
        $peminjaman->buku->increment('StokTersedia');
        $this->confirmingReturn = null;
        session()->flash('success', 'Buku berhasil dikembalikan.');
    }

    public function checkOverdue(): void
    {
        $overdue = Peminjaman::where('StatusPeminjaman', StatusPeminjaman::DIPINJAM)
            ->where('TanggalPengembalian', '<', now()->format('Y-m-d'))
            ->get();

        foreach ($overdue as $p) {
            $p->update(['StatusPeminjaman' => StatusPeminjaman::TERLAMBAT]);
        }

        session()->flash('success', "{$overdue->count()} peminjaman ditandai terlambat.");
    }

    public function render()
    {
        $query = Peminjaman::with(['user', 'buku'])
            ->when($this->search, fn($q) =>
                $q->whereHas('user', fn($uq) =>
                    $uq->where('NamaLengkap', 'like', "%{$this->search}%")
                       ->orWhere('email', 'like', "%{$this->search}%")
                )->orWhereHas('buku', fn($bq) =>
                    $bq->where('Judul', 'like', "%{$this->search}%")
                )
            )
            ->when($this->statusFilter, fn($q) =>
                $q->where('StatusPeminjaman', $this->statusFilter)
            )
            ->latest();

        return view('livewire.peminjaman.peminjaman-manage', [
            'peminjamanList' => $query->paginate(15),
            'statusOptions' => StatusPeminjaman::cases(),
        ]);
    }
}