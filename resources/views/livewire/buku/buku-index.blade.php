<div>
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-1 gap-3">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Cari judul, penulis, atau ISBN..."
                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            />
            <select
                wire:model.live="kategoriFilter"
                class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
            >
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $kat)
                    <option value="{{ $kat->KategoriID }}">{{ $kat->NamaKategori }}</option>
                @endforeach
            </select>
        </div>
        @if(auth()->check() && auth()->user()->isStaff())
            <a href="{{ route('buku.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
                </svg>
                Tambah Buku
            </a>
        @endif
    </div>

    @if($bukuList->isEmpty())
        <div class="rounded-xl bg-gray-50 px-6 py-14 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900">Tidak ada buku ditemukan</h3>
            <p class="mt-1 text-sm text-gray-500">Coba ubah kata kunci pencarian Anda.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($bukuList as $buku)
                <div class="group overflow-hidden rounded-xl bg-white shadow ring-1 ring-gray-200 transition hover:shadow-md hover:ring-indigo-300">
                    <a href="{{ route('buku.show', $buku->BukuID) }}">
                        <div class="aspect-[3/4] w-full overflow-hidden bg-gray-100">
                            @if($buku->CoverImage)
                                <img src="{{ asset('storage/' . $buku->CoverImage) }}" alt="{{ $buku->Judul }}"
                                     class="h-full w-full object-cover transition group-hover:scale-105"/>
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-indigo-100 to-purple-100">
                                    <svg class="h-16 w-16 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </a>
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <a href="{{ route('buku.show', $buku->BukuID) }}"
                                   class="block truncate text-sm font-semibold text-gray-900 hover:text-indigo-600">
                                    {{ $buku->Judul }}
                                </a>
                                <p class="mt-0.5 truncate text-xs text-gray-500">{{ $buku->Penulis }}</p>
                            </div>
                            <span class="shrink-0 rounded-full px-2 py-1 text-xs font-medium
                                {{ $buku->isAvailable() ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $buku->isAvailable() ? 'Tersedia' : 'Habis' }}
                            </span>
                        </div>
                        <div class="mt-2 flex flex-wrap gap-1">
                            @foreach($buku->kategori->take(2) as $kat)
                                <span class="rounded-full bg-indigo-50 px-2 py-0.5 text-xs text-indigo-700">
                                    {{ $kat->NamaKategori }}
                                </span>
                            @endforeach
                        </div>
                        <div class="mt-3 flex items-center justify-between text-xs text-gray-500">
                            <span>Stok: {{ $buku->StokTersedia }}/{{ $buku->StokTotal }}</span>
                            <span>{{ $buku->TahunTerbit }}</span>
                        </div>
                        @if(auth()->check() && auth()->user()->isStaff())
                            <div class="mt-3 flex gap-2">
                                <a href="{{ route('buku.edit', $buku->BukuID) }}"
                                   class="flex-1 rounded-lg bg-gray-100 py-1.5 text-center text-xs font-medium text-gray-700 hover:bg-gray-200">
                                    Edit
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $bukuList->links() }}
        </div>
    @endif
</div>