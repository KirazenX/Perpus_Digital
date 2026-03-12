<div>
    {{-- Filter --}}
    <div class="mb-6 rounded-xl bg-white p-4 shadow ring-1 ring-gray-200">
        <div class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="text-xs font-medium text-gray-500">Dari Tanggal</label>
                <input wire:model.live="dateFrom" type="date"
                       class="mt-1 block rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500">Sampai Tanggal</label>
                <input wire:model.live="dateTo" type="date"
                       class="mt-1 block rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500">Filter Status</label>
                <select wire:model.live="statusFilter"
                        class="mt-1 block rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Semua Status</option>
                    @foreach($statusOptions as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500">Tampilkan</label>
                <select wire:model.live="reportType"
                        class="mt-1 block rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="peminjaman">Detail Peminjaman</option>
                    <option value="buku_populer">Buku Paling Populer</option>
                    <option value="pengguna_aktif">Pengguna Paling Aktif</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
        @php
            $statCards = [
                ['label' => 'Total', 'value' => $statistik['total'], 'color' => 'indigo'],
                ['label' => 'Menunggu', 'value' => $statistik['menunggu'], 'color' => 'yellow'],
                ['label' => 'Dipinjam', 'value' => $statistik['dipinjam'], 'color' => 'blue'],
                ['label' => 'Dikembalikan', 'value' => $statistik['dikembalikan'], 'color' => 'green'],
                ['label' => 'Terlambat', 'value' => $statistik['terlambat'], 'color' => 'red'],
                ['label' => 'Ditolak', 'value' => $statistik['ditolak'], 'color' => 'gray'],
            ];
        @endphp
        @foreach($statCards as $card)
            <div class="rounded-xl bg-white p-4 shadow ring-1 ring-gray-200 text-center">
                <p class="text-2xl font-bold text-{{ $card['color'] }}-600">{{ $card['value'] }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $card['label'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Content based on reportType --}}
    @if($reportType === 'peminjaman')
        <div class="overflow-hidden rounded-xl bg-white shadow ring-1 ring-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Peminjam</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Buku</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Tgl Pinjam</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Tgl Kembali</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($peminjamanData as $p)
                        <tr>
                            <td class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-900">{{ $p->user->NamaLengkap ?? $p->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $p->user->email }}</p>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 max-w-xs truncate">{{ $p->buku->Judul }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $p->TanggalPeminjaman->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $p->TanggalPengembalian->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $p->StatusPeminjaman->badge() }}">
                                    {{ $p->StatusPeminjaman->label() }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-12 text-center text-sm text-gray-500">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">{{ $peminjamanData->links() }}</div>
        </div>

    @elseif($reportType === 'buku_populer')
        <div class="overflow-hidden rounded-xl bg-white shadow ring-1 ring-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Buku</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Total Dipinjam</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($bukuPopuler as $i => $item)
                        <tr>
                            <td class="px-4 py-3 text-sm font-bold text-gray-400">{{ $i + 1 }}</td>
                            <td class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-900">{{ $item->buku->Judul }}</p>
                                <p class="text-xs text-gray-500">{{ $item->buku->Penulis }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 rounded-full bg-indigo-500" style="width: {{ ($item->total_dipinjam / ($bukuPopuler->first()->total_dipinjam ?? 1)) * 120 }}px; max-width: 120px;"></div>
                                    <span class="text-sm font-semibold text-gray-900">{{ $item->total_dipinjam }}x</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @elseif($reportType === 'pengguna_aktif')
        <div class="overflow-hidden rounded-xl bg-white shadow ring-1 ring-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">#</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Pengguna</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Total Pinjam</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($penggunaAktif as $i => $item)
                        <tr>
                            <td class="px-4 py-3 text-sm font-bold text-gray-400">{{ $i + 1 }}</td>
                            <td class="px-4 py-3">
                                <p class="text-sm font-medium text-gray-900">{{ $item->user->NamaLengkap ?? $item->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $item->user->email }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 rounded-full bg-green-500" style="width: {{ ($item->total_pinjam / ($penggunaAktif->first()->total_pinjam ?? 1)) * 120 }}px; max-width: 120px;"></div>
                                    <span class="text-sm font-semibold text-gray-900">{{ $item->total_pinjam }}x</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>