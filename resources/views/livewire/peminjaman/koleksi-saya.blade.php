<div>
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <flux:heading size="xl">Koleksi Saya</flux:heading>
            <flux:subheading class="mt-1">Buku-buku yang Anda simpan sebagai favorit</flux:subheading>
        </div>
        <flux:input
            wire:model.live.debounce.300ms="search"
            icon="magnifying-glass"
            placeholder="Cari judul atau penulis..."
            class="sm:w-72"
        />
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-6">{{ session('success') }}</flux:callout>
    @endif

    @if($koleksiList->isEmpty())
        <div class="rounded-2xl border border-dashed border-zinc-300 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900/50 px-6 py-20 text-center">
            <flux:icon name="star" class="mx-auto size-12 text-zinc-400 dark:text-zinc-600" />
            <flux:heading size="lg" class="mt-4">Koleksi masih kosong</flux:heading>
            <flux:text class="mt-2 mb-6">Simpan buku favorit Anda dari halaman katalog.</flux:text>
            <flux:button href="{{ route('buku.index') }}" variant="primary" wire:navigate>
                Jelajahi Katalog
            </flux:button>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($koleksiList as $koleksi)
                <div wire:key="koleksi-{{ $koleksi->KoleksiID }}" class="group flex flex-col overflow-hidden rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm transition hover:shadow-lg hover:border-indigo-500 dark:hover:border-indigo-500">
                    <a href="{{ route('buku.show', $koleksi->buku->BukuID) }}" wire:navigate class="block relative aspect-[3/4] overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                        @if($koleksi->buku->CoverImage)
                            <img src="{{ asset('storage/' . $koleksi->buku->CoverImage) }}" alt="{{ $koleksi->buku->Judul }}"
                                 class="h-full w-full object-cover transition duration-300 group-hover:scale-105"/>
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-950/30 dark:to-purple-950/30">
                                <flux:icon name="book-open" class="size-16 text-indigo-200 dark:text-indigo-900" />
                            </div>
                        @endif
                        <div class="absolute top-2 right-2">
                            <flux:badge :variant="$koleksi->buku->isAvailable() ? 'success' : 'danger'" size="sm">
                                {{ $koleksi->buku->isAvailable() ? 'Tersedia' : 'Habis' }}
                            </flux:badge>
                        </div>
                    </a>

                    <div class="flex flex-1 flex-col p-5">
                        <div class="flex-1">
                            <div class="flex flex-wrap gap-1 mb-2">
                                @foreach($koleksi->buku->kategori->take(2) as $kat)
                                    <flux:badge size="sm" variant="neutral" inset="top bottom" class="text-[10px] uppercase tracking-wider">
                                        {{ $kat->NamaKategori }}
                                    </flux:badge>
                                @endforeach
                            </div>
                            <a href="{{ route('buku.show', $koleksi->buku->BukuID) }}" wire:navigate
                               class="block line-clamp-2 text-base font-bold text-zinc-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400">
                                {{ $koleksi->buku->Judul }}
                            </a>
                            <p class="mt-1 line-clamp-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $koleksi->buku->Penulis }}</p>
                        </div>

                        <div class="mt-4 flex gap-2 border-t border-zinc-100 dark:border-zinc-800 pt-4">
                            @if($koleksi->buku->isAvailable())
                                <flux:button href="{{ route('peminjaman.create', $koleksi->buku->BukuID) }}" variant="primary" size="sm" class="flex-1" wire:navigate>
                                    Pinjam
                                </flux:button>
                            @else
                                <flux:button disabled variant="filled" size="sm" class="flex-1">
                                    Stok Habis
                                </flux:button>
                            @endif
                            <flux:button wire:click="hapusDariKoleksi({{ $koleksi->buku->BukuID }})" variant="ghost" size="sm" icon="trash" class="text-red-500 hover:text-red-600">
                            </flux:button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $koleksiList->links() }}
        </div>
    @endif
</div>
