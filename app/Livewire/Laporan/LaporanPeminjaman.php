<?php

namespace App\Livewire\Laporan;

use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use App\Enums\StatusPeminjaman;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class LaporanPeminjaman extends Component
{
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $statusFilter = '';
    public string $reportType = 'peminjaman';

    public function mount(): void
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->endOfMonth()->format('Y-m-d');
    }

    public function getStatistikProperty(): array
    {
        $baseQuery = Peminjaman::whereBetween('TanggalPeminjaman', [$this->dateFrom, $this->dateTo]);

        return [
            'total' => $baseQuery->count(),
            'menunggu' => (clone $baseQuery)->where('StatusPeminjaman', StatusPeminjaman::MENUNGGU)->count(),
            'dipinjam' => (clone $baseQuery)->where('StatusPeminjaman', StatusPeminjaman::DIPINJAM)->count(),
            'dikembalikan' => (clone $baseQuery)->where('StatusPeminjaman', StatusPeminjaman::DIKEMBALIKAN)->count(),
            'terlambat' => (clone $baseQuery)->where('StatusPeminjaman', StatusPeminjaman::TERLAMBAT)->count(),
            'ditolak' => (clone $baseQuery)->where('StatusPeminjaman', StatusPeminjaman::DITOLAK)->count(),
        ];
    }

    public function getPeminjamanDataProperty()
    {
        return Peminjaman::with(['user', 'buku'])
            ->whereBetween('TanggalPeminjaman', [$this->dateFrom, $this->dateTo])
            ->when($this->statusFilter, fn($q) => $q->where('StatusPeminjaman', $this->statusFilter))
            ->latest()
            ->paginate(20);
    }

    public function getBukuPopulerProperty()
    {
        return Peminjaman::with('buku')
            ->whereBetween('TanggalPeminjaman', [$this->dateFrom, $this->dateTo])
            ->select('BukuID', DB::raw('COUNT(*) as total_dipinjam'))
            ->groupBy('BukuID')
            ->orderByDesc('total_dipinjam')
            ->limit(10)
            ->get();
    }

    public function getPenggunaAktifProperty()
    {
        return Peminjaman::with('user')
            ->whereBetween('TanggalPeminjaman', [$this->dateFrom, $this->dateTo])
            ->select('UserID', DB::raw('COUNT(*) as total_pinjam'))
            ->groupBy('UserID')
            ->orderByDesc('total_pinjam')
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.laporan.laporan-peminjaman', [
            'statistik' => $this->getStatistikProperty(),
            'peminjamanData' => $this->getPeminjamanDataProperty(),
            'bukuPopuler' => $this->getBukuPopulerProperty(),
            'penggunaAktif' => $this->getPenggunaAktifProperty(),
            'statusOptions' => StatusPeminjaman::cases(),
        ]);
    }
}