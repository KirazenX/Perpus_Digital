<div>
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <flux:heading size="xl">Laporan Peminjaman</flux:heading>
        <flux:button icon="printer" variant="outline" x-on:click="window.print()">Cetak Laporan</flux:button>
    </div>

    {{-- Filter --}}
    <div class="mb-8 rounded-2xl bg-white dark:bg-zinc-900 p-6 shadow-sm border border-zinc-200 dark:border-zinc-800">
        <div class="flex flex-wrap gap-6 items-end">
            <div class="flex-1 min-w-[200px]">
                <flux:field>
                    <flux:label>Dari Tanggal</flux:label>
                    <flux:input wire:model.live="dateFrom" type="date" />
                </flux:field>
            </div>
            <div class="flex-1 min-w-[200px]">
                <flux:field>
                    <flux:label>Sampai Tanggal</flux:label>
                    <flux:input wire:model.live="dateTo" type="date" />
                </flux:field>
            </div>
            <div class="flex-1 min-w-[200px]">
                <flux:select wire:model.live="statusFilter" label="Filter Status">
                    <flux:select.option value="">Semua Status</flux:select.option>
                    @foreach($statusOptions as $status)
                        <flux:select.option value="{{ $status->value }}">{{ $status->label() }}</flux:select.option>
                    @endforeach
                </flux:select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <flux:select wire:model.live="reportType" label="Tampilkan Analisis">
                    <flux:select.option value="peminjaman">Detail Peminjaman</flux:select.option>
                    <flux:select.option value="buku_populer">Buku Terpopuler</flux:select.option>
                    <flux:select.option value="pengguna_aktif">Pengguna Teraktif</flux:select.option>
                </flux:select>
            </div>
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="mb-8 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
        @php
            $statCards = [
                ['label' => 'Total', 'value' => $statistik['total'], 'color' => 'indigo', 'icon' => 'list-bullet'],
                ['label' => 'Menunggu', 'value' => $statistik['menunggu'], 'color' => 'amber', 'icon' => 'clock'],
                ['label' => 'Dipinjam', 'value' => $statistik['dipinjam'], 'color' => 'blue', 'icon' => 'arrow-up-tray'],
                ['label' => 'Kembali', 'value' => $statistik['dikembalikan'], 'color' => 'emerald', 'icon' => 'arrow-down-tray'],
                ['label' => 'Terlambat', 'value' => $statistik['terlambat'], 'color' => 'red', 'icon' => 'exclamation-triangle'],
                ['label' => 'Ditolak', 'value' => $statistik['ditolak'], 'color' => 'zinc', 'icon' => 'x-circle'],
            ];
        @endphp
        @foreach($statCards as $card)
            <div class="rounded-2xl bg-white dark:bg-zinc-900 p-4 shadow-sm border border-zinc-200 dark:border-zinc-800 text-center flex flex-col items-center">
                <div class="rounded-full p-2 mb-2 bg-{{ $card['color'] }}-50 dark:bg-{{ $card['color'] }}-950/30 text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-400">
                    <flux:icon name="{{ $card['icon'] }}" class="size-4" />
                </div>
                <flux:text size="sm" class="font-bold text-zinc-900 dark:text-zinc-100">{{ $card['value'] }}</flux:text>
                <flux:text class="text-[10px] uppercase font-bold text-zinc-400 tracking-wider mt-1">{{ $card['label'] }}</flux:text>
            </div>
        @endforeach
    </div>

    {{-- Content based on reportType --}}
    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden shadow-sm">
        @if($reportType === 'peminjaman')
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Peminjam</flux:table.column>
                    <flux:table.column>Buku</flux:table.column>
                    <flux:table.column>Tgl Pinjam</flux:table.column>
                    <flux:table.column>Tgl Kembali</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($peminjamanData as $p)
                        <flux:table.row wire:key="report-pinjam-{{ $p->PeminjamanID }}">
                            <flux:table.cell>
                                <div class="flex flex-col">
                                    <span class="font-bold text-zinc-900 dark:text-zinc-100">{{ $p->user->NamaLengkap ?? $p->user->name }}</span>
                                    <span class="text-xs text-zinc-500">{{ $p->user->email }}</span>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <span class="font-medium text-zinc-900 dark:text-zinc-200 truncate max-w-xs block">{{ $p->buku->Judul }}</span>
                            </flux:table.cell>
                            <flux:table.cell class="text-xs text-zinc-600 dark:text-zinc-400">{{ $p->TanggalPeminjaman->translatedFormat('d M Y') }}</flux:table.cell>
                            <flux:table.cell class="text-xs text-zinc-600 dark:text-zinc-400">{{ $p->TanggalPengembalian->translatedFormat('d M Y') }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge :variant="$p->StatusPeminjaman->color()" size="sm" inset="top bottom">
                                    {{ $p->StatusPeminjaman->label() }}
                                </flux:badge>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="py-12 text-center text-zinc-500">Tidak ada data peminjaman.</flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
            @if($peminjamanData->hasPages())
                <div class="p-4 border-t border-zinc-100 dark:border-zinc-800">
                    {{ $peminjamanData->links() }}
                </div>
            @endif

        @elseif($reportType === 'buku_populer')
            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="w-12">#</flux:table.column>
                    <flux:table.column>Buku</flux:table.column>
                    <flux:table.column>Total Dipinjam</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach($bukuPopuler as $i => $item)
                        <flux:table.row wire:key="populer-buku-{{ $item->BukuID }}">
                            <flux:table.cell class="font-bold text-zinc-400">{{ $i + 1 }}</flux:table.cell>
                            <flux:table.cell>
                                <div class="flex flex-col">
                                    <span class="font-bold text-zinc-900 dark:text-zinc-100">{{ $item->buku->Judul }}</span>
                                    <span class="text-xs text-zinc-500">{{ $item->buku->Penulis }}</span>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 max-w-[200px] h-2 rounded-full bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
                                        <div class="h-full bg-indigo-500" style="width: {{ ($item->total_dipinjam / ($bukuPopuler->first()->total_dipinjam ?? 1)) * 100 }}%"></div>
                                    </div>
                                    <span class="text-sm font-bold text-zinc-900 dark:text-zinc-100">{{ $item->total_dipinjam }}x</span>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>

        @elseif($reportType === 'pengguna_aktif')
            <flux:table>
                <flux:table.columns>
                    <flux:table.column class="w-12">#</flux:table.column>
                    <flux:table.column>Pengguna</flux:table.column>
                    <flux:table.column>Total Pinjam</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach($penggunaAktif as $i => $item)
                        <flux:table.row wire:key="aktif-user-{{ $item->UserID }}">
                            <flux:table.cell class="font-bold text-zinc-400">{{ $i + 1 }}</flux:table.cell>
                            <flux:table.cell>
                                <div class="flex items-center gap-3">
                                    <flux:avatar :name="$item->user->name" size="xs" />
                                    <div class="flex flex-col">
                                        <span class="font-bold text-zinc-900 dark:text-zinc-100">{{ $item->user->NamaLengkap ?? $item->user->name }}</span>
                                        <span class="text-xs text-zinc-500">{{ $item->user->email }}</span>
                                    </div>
                                </div>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 max-w-[200px] h-2 rounded-full bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
                                        <div class="h-full bg-emerald-500" style="width: {{ ($item->total_pinjam / ($penggunaAktif->first()->total_pinjam ?? 1)) * 100 }}%"></div>
                                    </div>
                                    <span class="text-sm font-bold text-zinc-900 dark:text-zinc-100">{{ $item->total_pinjam }}x</span>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        @endif
    </div>
</div>