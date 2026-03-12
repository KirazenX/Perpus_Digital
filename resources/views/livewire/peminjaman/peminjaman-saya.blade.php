<div>
    <div class="mb-8 flex items-center justify-between">
        <flux:heading size="xl">Peminjaman Saya</flux:heading>
        <flux:select wire:model.live="statusFilter" placeholder="Filter Status" class="w-48">
            <flux:select.option value="">Semua Status</flux:select.option>
            @foreach($statusOptions as $status)
                <flux:select.option value="{{ $status->value }}">{{ $status->label() }}</flux:select.option>
            @endforeach
        </flux:select>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-6">{{ session('success') }}</flux:callout>
    @endif

    @if($peminjamanList->isEmpty())
        <div class="rounded-2xl border border-dashed border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/50 px-6 py-20 text-center">
            <flux:icon name="clipboard-document-list" class="mx-auto size-12 text-zinc-400 dark:text-zinc-600" />
            <flux:heading size="lg" class="mt-4">Belum ada peminjaman</flux:heading>
            <flux:text class="mt-2 mb-6">Kunjungi katalog dan mulai pinjam buku favorit Anda.</flux:text>
            <flux:button href="{{ route('buku.index') }}" variant="primary" wire:navigate>
                Lihat Katalog Buku
            </flux:button>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            @foreach($peminjamanList as $p)
                <div wire:key="pinjam-{{ $p->PeminjamanID }}" class="flex gap-5 rounded-2xl bg-white dark:bg-zinc-900 p-5 border border-zinc-200 dark:border-zinc-800 shadow-sm transition hover:border-indigo-500">
                    <a href="{{ route('buku.show', $p->buku->BukuID) }}" class="shrink-0">
                        <div class="overflow-hidden rounded-xl shadow-sm ring-1 ring-zinc-200 dark:ring-zinc-700">
                            @if($p->buku->CoverImage)
                                <img src="{{ asset('storage/' . $p->buku->CoverImage) }}" alt="{{ $p->buku->Judul }}"
                                     class="h-28 w-20 object-cover"/>
                            @else
                                <div class="flex h-28 w-20 items-center justify-center bg-indigo-50 dark:bg-indigo-950/30">
                                    <flux:icon name="book-open" class="size-8 text-indigo-200 dark:text-indigo-900" />
                                </div>
                            @endif
                        </div>
                    </a>
                    
                    <div class="flex-1 min-w-0 flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-2 mb-1">
                                <a href="{{ route('buku.show', $p->buku->BukuID) }}"
                                   class="text-base font-bold text-zinc-900 dark:text-zinc-100 hover:text-indigo-600 truncate">
                                    {{ $p->buku->Judul }}
                                </a>
                                <flux:badge :variant="$p->StatusPeminjaman->color()" size="sm">
                                    {{ $p->StatusPeminjaman->label() }}
                                </flux:badge>
                            </div>
                            <flux:text size="sm" class="truncate">{{ $p->buku->Penulis }}</flux:text>
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-4 border-t border-zinc-100 dark:border-zinc-800 pt-4">
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase font-bold text-zinc-400">Tgl Pinjam</span>
                                <span class="text-xs font-medium dark:text-zinc-300">{{ $p->TanggalPeminjaman->translatedFormat('d M Y') }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase font-bold text-zinc-400">Tgl Kembali</span>
                                <span class="text-xs font-medium {{ $p->isTerlambat() ? 'text-red-600 font-bold' : 'dark:text-zinc-300' }}">
                                    {{ $p->TanggalPengembalian->translatedFormat('d M Y') }}
                                </span>
                            </div>
                        </div>
                        
                        @if($p->TanggalDikembalikan)
                            <div class="mt-2 text-[10px] text-emerald-600 dark:text-emerald-400 font-medium">
                                Dikembalikan pada {{ $p->TanggalDikembalikan->translatedFormat('d M Y') }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">
            {{ $peminjamanList->links() }}
        </div>
    @endif
</div>