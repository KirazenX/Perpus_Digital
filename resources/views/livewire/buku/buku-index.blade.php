<div>
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-1 flex-col sm:flex-row gap-3">
            <flux:input
                wire:model.live.debounce.300ms="search"
                icon="magnifying-glass"
                placeholder="Cari judul atau penulis buku..."
                class="flex-1"
            />
            <flux:select
                wire:model.live="kategoriFilter"
                placeholder="Semua Kategori"
                class="sm:w-64"
            >
                <flux:select.option value="">Semua Kategori</flux:select.option>
                @foreach($kategoriList as $kat)
                    <flux:select.option value="{{ $kat->KategoriID }}">{{ $kat->NamaKategori }}</flux:select.option>
                @endforeach
            </flux:select>
        </div>
        @if(auth()->check() && auth()->user()->isStaff())
            <flux:button href="{{ route('buku.create') }}" variant="primary" icon="plus" wire:navigate>
                Tambah Buku
            </flux:button>
        @endif
    </div>

    @if($bukuList->isEmpty())
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/50 px-6 py-20 text-center">
            <flux:icon name="book-open" class="mx-auto size-12 text-zinc-400 dark:text-zinc-600" />
            <flux:heading size="lg" class="mt-4">Tidak ada buku ditemukan</flux:heading>
            <flux:text class="mt-2">Coba ubah kata kunci pencarian atau kategori Anda.</flux:text>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($bukuList as $buku)
                <div wire:key="buku-{{ $buku->BukuID }}" class="group flex flex-col overflow-hidden rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm transition hover:shadow-lg hover:border-indigo-500 dark:hover:border-indigo-500">
                    <a href="{{ route('buku.show', $buku->BukuID) }}" class="block relative aspect-[3/4] overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                        @if($buku->CoverImage)
                            <img src="{{ asset('storage/' . $buku->CoverImage) }}" alt="{{ $buku->Judul }}"
                                 class="h-full w-full object-cover transition duration-300 group-hover:scale-105"/>
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-linear-to-br from-indigo-50 to-purple-50 dark:from-indigo-950/30 dark:to-purple-950/30">
                                <flux:icon name="book-open" class="size-16 text-indigo-200 dark:text-indigo-900" />
                            </div>
                        @endif

                        <div class="absolute top-2 right-2">
                            <flux:badge :variant="$buku->isAvailable() ? 'success' : 'danger'" size="sm">
                                {{ $buku->isAvailable() ? 'Tersedia' : 'Habis' }}
                            </flux:badge>
                        </div>
                    </a>

                    <div class="flex flex-1 flex-col p-5">
                        <div class="flex-1">
                            <div class="flex flex-wrap gap-1 mb-2">
                                @foreach($buku->kategori->take(2) as $kat)
                                    <flux:badge size="sm" variant="neutral" inset="top bottom" class="text-[10px] uppercase tracking-wider">
                                        {{ $kat->NamaKategori }}
                                    </flux:badge>
                                @endforeach
                            </div>
                            <a href="{{ route('buku.show', $buku->BukuID) }}"
                               class="block line-clamp-2 text-base font-bold text-zinc-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400">
                                {{ $buku->Judul }}
                            </a>
                            <p class="mt-1 line-clamp-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $buku->Penulis }}</p>
                        </div>

                        <div class="mt-4 flex items-center justify-between border-t border-zinc-100 dark:border-zinc-800 pt-4">
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase text-zinc-400 dark:text-zinc-500 font-semibold tracking-tight">Stok</span>
                                <span class="text-sm font-medium dark:text-zinc-300">{{ $buku->StokTersedia }}/{{ $buku->StokTotal }}</span>
                            </div>
                            <div class="flex flex-col text-right">
                                <span class="text-[10px] uppercase text-zinc-400 dark:text-zinc-500 font-semibold tracking-tight">Tahun</span>
                                <span class="text-sm font-medium dark:text-zinc-300">{{ $buku->TahunTerbit }}</span>
                            </div>
                        </div>

                        @if(auth()->check() && auth()->user()->isStaff())
                            <div class="mt-4 flex gap-2">
                                <flux:button href="{{ route('buku.edit', $buku->BukuID) }}" variant="outline" size="sm" icon="pencil" class="flex-1">
                                    Edit
                                </flux:button>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $bukuList->links() }}
        </div>
    @endif
</div>