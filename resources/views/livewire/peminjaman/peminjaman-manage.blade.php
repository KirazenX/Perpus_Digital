<div>
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700 flex justify-between">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Toolbar --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-1 gap-3">
            <input wire:model.live.debounce.300ms="search" type="text"
                   placeholder="Cari peminjam atau buku..."
                   class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
            <select wire:model.live="statusFilter"
                    class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">Semua Status</option>
                @foreach($statusOptions as $status)
                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>
        <button wire:click="checkOverdue"
                class="rounded-lg bg-orange-500 px-4 py-2 text-sm font-semibold text-white hover:bg-orange-400">
            Cek Keterlambatan
        </button>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl bg-white shadow ring-1 ring-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Peminjam</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Buku</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Tgl Pinjam</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Tgl Kembali</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($peminjamanList as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="text-sm font-medium text-gray-900">{{ $p->user->NamaLengkap ?? $p->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $p->user->email }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm text-gray-900 max-w-xs truncate">{{ $p->buku->Judul }}</p>
                            <p class="text-xs text-gray-500">{{ $p->buku->Penulis }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $p->TanggalPeminjaman->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $p->TanggalPengembalian->format('d M Y') }}
                            @if($p->TanggalDikembalikan)
                                <p class="text-xs text-green-600">Kembali: {{ $p->TanggalDikembalikan->format('d M Y') }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $p->StatusPeminjaman->badge() }}">
                                {{ $p->StatusPeminjaman->label() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                @if($p->StatusPeminjaman->value === 'menunggu')
                                    @if($confirmingApprove === $p->PeminjamanID)
                                        <span class="text-xs text-gray-500">Yakin?</span>
                                        <button wire:click="approve({{ $p->PeminjamanID }})" class="text-xs font-semibold text-green-600 hover:text-green-800">Ya</button>
                                        <button wire:click="$set('confirmingApprove', null)" class="text-xs font-semibold text-gray-500 hover:text-gray-700">Tidak</button>
                                    @else
                                        <button wire:click="$set('confirmingApprove', {{ $p->PeminjamanID }})"
                                                class="rounded bg-green-100 px-2 py-1 text-xs font-semibold text-green-700 hover:bg-green-200">
                                            Setujui
                                        </button>
                                    @endif
                                    @if($confirmingReject === $p->PeminjamanID)
                                        <span class="text-xs text-gray-500">Yakin?</span>
                                        <button wire:click="reject({{ $p->PeminjamanID }})" class="text-xs font-semibold text-red-600 hover:text-red-800">Ya</button>
                                        <button wire:click="$set('confirmingReject', null)" class="text-xs font-semibold text-gray-500">Tidak</button>
                                    @else
                                        <button wire:click="$set('confirmingReject', {{ $p->PeminjamanID }})"
                                                class="rounded bg-red-100 px-2 py-1 text-xs font-semibold text-red-700 hover:bg-red-200">
                                            Tolak
                                        </button>
                                    @endif
                                @elseif(in_array($p->StatusPeminjaman->value, ['dipinjam', 'terlambat']))
                                    @if($confirmingReturn === $p->PeminjamanID)
                                        <span class="text-xs text-gray-500">Konfirmasi pengembalian?</span>
                                        <button wire:click="returnBook({{ $p->PeminjamanID }})" class="text-xs font-semibold text-blue-600 hover:text-blue-800">Ya</button>
                                        <button wire:click="$set('confirmingReturn', null)" class="text-xs font-semibold text-gray-500">Tidak</button>
                                    @else
                                        <button wire:click="$set('confirmingReturn', {{ $p->PeminjamanID }})"
                                                class="rounded bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700 hover:bg-blue-200">
                                            Kembalikan
                                        </button>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-sm text-gray-500">
                            Tidak ada data peminjaman.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $peminjamanList->links() }}</div>
</div>