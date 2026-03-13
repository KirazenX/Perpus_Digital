<div>
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <flux:heading size="xl">Kelola Peminjaman</flux:heading>
        <flux:button wire:click="checkOverdue" variant="filled" icon="clock">
            Cek Keterlambatan
        </flux:button>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-6">{{ session('success') }}</flux:callout>
    @endif
    @if(session('error'))
        <flux:callout variant="danger" class="mb-6">{{ session('error') }}</flux:callout>
    @endif

    <flux:card class="mb-6">
        <div class="flex flex-col gap-4 sm:flex-row">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Cari peminjam atau buku..." class="flex-1" />
            <flux:select wire:model.live="statusFilter" placeholder="Semua Status" class="sm:w-64">
                <flux:select.option value="">Semua Status</flux:select.option>
                @foreach($statusOptions as $status)
                    <flux:select.option value="{{ $status->value }}">{{ $status->label() }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>
    </flux:card>

    <flux:card class="overflow-hidden p-0!">
        <flux:table container:class="px-3">
            <flux:table.columns>
                <flux:table.column>Peminjam</flux:table.column>
                <flux:table.column>Buku</flux:table.column>
                <flux:table.column>Periode</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column align="right">Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($peminjamanList as $p)
                    <flux:table.row wire:key="manage-pinjam-{{ $p->PeminjamanID }}">
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                <flux:avatar :name="$p->user->name" size="xs" />
                                <div class="flex flex-col">
                                    <span class="font-bold text-zinc-900 dark:text-zinc-100">{{ $p->user->NamaLengkap ?? $p->user->name }}</span>
                                    <span class="text-xs text-zinc-500">{{ $p->user->email }}</span>
                                </div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex flex-col max-w-xs">
                                <span class="font-medium text-zinc-900 dark:text-zinc-200 truncate">{{ $p->buku->Judul }}</span>
                                <span class="text-xs text-zinc-500">{{ $p->buku->Penulis }}</span>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <div class="flex flex-col text-xs text-zinc-600 dark:text-zinc-400">
                                <span>P: {{ $p->TanggalPeminjaman->translatedFormat('d M Y') }}</span>
                                <span class="{{ $p->isTerlambat() ? 'text-red-600 font-bold' : '' }}">K: {{ $p->TanggalPengembalian->translatedFormat('d M Y') }}</span>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge :variant="$p->StatusPeminjaman->color()" size="sm" inset="top bottom">
                                {{ $p->StatusPeminjaman->label() }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell align="right">
                            <div class="flex justify-end gap-2">
                                @if($p->StatusPeminjaman->value === 'menunggu')
                                    <flux:button wire:click="$set('confirmingApprove', {{ $p->PeminjamanID }})" variant="ghost" size="sm" class="text-emerald-600">Setujui</flux:button>
                                    <flux:button wire:click="$set('confirmingReject', {{ $p->PeminjamanID }})" variant="ghost" size="sm" class="text-red-600">Tolak</flux:button>
                                @elseif(in_array($p->StatusPeminjaman->value, ['dipinjam', 'terlambat']))
                                    <flux:button wire:click="$set('confirmingReturn', {{ $p->PeminjamanID }})" variant="primary" size="sm">Kembalikan</flux:button>
                                @else
                                    <span class="text-zinc-400">—</span>
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="py-12 text-center text-zinc-500">
                            Tidak ada data peminjaman yang ditemukan.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </flux:card>

    <div class="mt-6">
        {{ $peminjamanList->links() }}
    </div>

    {{-- Modals for confirmation --}}
    <flux:modal wire:model="confirmingApprove" class="md:w-96">
        <div class="space-y-6">
            <flux:heading size="lg">Konfirmasi Persetujuan</flux:heading>
            <flux:text>Apakah Anda yakin ingin menyetujui peminjaman buku ini?</flux:text>
            <div class="flex gap-3 justify-end">
                <flux:modal.close><flux:button variant="ghost">Batal</flux:button></flux:modal.close>
                <flux:button wire:click="approve({{ $confirmingApprove }})" variant="primary">Ya, Setujui</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal wire:model="confirmingReject" class="md:w-96">
        <div class="space-y-6">
            <flux:heading size="lg">Konfirmasi Penolakan</flux:heading>
            <flux:text>Apakah Anda yakin ingin menolak permintaan peminjaman ini?</flux:text>
            <div class="flex gap-3 justify-end">
                <flux:modal.close><flux:button variant="ghost">Batal</flux:button></flux:modal.close>
                <flux:button wire:click="reject({{ $confirmingReject }})" variant="danger">Ya, Tolak</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal wire:model="confirmingReturn" class="md:w-96">
        <div class="space-y-6">
            <flux:heading size="lg">Konfirmasi Pengembalian</flux:heading>
            <flux:text>Apakah buku ini sudah dikembalikan dengan benar?</flux:text>
            <div class="flex gap-3 justify-end">
                <flux:modal.close><flux:button variant="ghost">Batal</flux:button></flux:modal.close>
                <flux:button wire:click="returnBook({{ $confirmingReturn }})" variant="primary">Ya, Sudah Kembali</flux:button>
            </div>
        </div>
    </flux:modal>
</div>