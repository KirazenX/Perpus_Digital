<div>
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
        {{-- Cover --}}
        <div class="lg:col-span-1">
            <div class="overflow-hidden rounded-xl bg-gray-100 shadow ring-1 ring-gray-200">
                @if($buku->CoverImage)
                    <img src="{{ asset('storage/' . $buku->CoverImage) }}" alt="{{ $buku->Judul }}" class="w-full object-cover"/>
                @else
                    <div class="flex aspect-[3/4] items-center justify-center bg-gradient-to-br from-indigo-100 to-purple-100">
                        <svg class="h-24 w-24 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="mt-4 space-y-3">
                @auth
                    @if(auth()->user()->isPeminjam())
                        @if($buku->isAvailable())
                            <a href="{{ route('peminjaman.create', $buku->BukuID) }}"
                               class="block w-full rounded-lg bg-indigo-600 py-2.5 text-center text-sm font-semibold text-white hover:bg-indigo-500">
                                Pinjam Buku
                            </a>
                        @else
                            <button disabled class="block w-full rounded-lg bg-gray-300 py-2.5 text-center text-sm font-semibold text-gray-500 cursor-not-allowed">
                                Stok Habis
                            </button>
                        @endif
                    @endif
                    @if(auth()->user()->isPeminjam() || auth()->user()->isPeminjam())
                        <button wire:click="toggleKoleksi"
                                class="block w-full rounded-lg border {{ $isInKoleksi ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-gray-300 bg-white text-gray-700' }} py-2.5 text-center text-sm font-semibold hover:bg-gray-50">
                            {{ $isInKoleksi ? '★ Hapus dari Koleksi' : '☆ Simpan ke Koleksi' }}
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                       class="block w-full rounded-lg bg-indigo-600 py-2.5 text-center text-sm font-semibold text-white hover:bg-indigo-500">
                        Login untuk Meminjam
                    </a>
                @endauth
            </div>
        </div>

        {{-- Detail --}}
        <div class="lg:col-span-2 space-y-6">
            <div>
                <div class="flex flex-wrap gap-2 mb-3">
                    @foreach($buku->kategori as $kat)
                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">
                            {{ $kat->NamaKategori }}
                        </span>
                    @endforeach
                </div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $buku->Judul }}</h1>
                <p class="mt-1 text-lg text-gray-600">{{ $buku->Penulis }}</p>

                <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3">
                    <div>
                        <p class="text-xs text-gray-500">Penerbit</p>
                        <p class="text-sm font-medium text-gray-900">{{ $buku->Penerbit }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Tahun Terbit</p>
                        <p class="text-sm font-medium text-gray-900">{{ $buku->TahunTerbit }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Stok Tersedia</p>
                        <p class="text-sm font-medium {{ $buku->isAvailable() ? 'text-green-600' : 'text-red-600' }}">
                            {{ $buku->StokTersedia }} / {{ $buku->StokTotal }} buku
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Rating</p>
                        <p class="text-sm font-medium text-amber-500">
                            @for($i = 1; $i <= 5; $i++)
                                {{ $i <= round($buku->averageRating()) ? '★' : '☆' }}
                            @endfor
                            <span class="text-gray-600">({{ $buku->ulasan->count() }} ulasan)</span>
                        </p>
                    </div>
                </div>

                @if($buku->Deskripsi)
                <div class="mt-4">
                    <h3 class="text-sm font-semibold text-gray-900">Deskripsi</h3>
                    <p class="mt-1 text-sm text-gray-600 leading-relaxed">{{ $buku->Deskripsi }}</p>
                </div>
                @endif
            </div>

            {{-- Ulasan --}}
            <div class="rounded-xl bg-white p-6 shadow ring-1 ring-gray-200">
                <h3 class="text-base font-semibold text-gray-900">Ulasan Pembaca</h3>

                @auth
                @if(auth()->user()->isPeminjam())
                <form wire:submit="submitUlasan" class="mt-4 space-y-3">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Rating</label>
                        <div class="mt-1 flex gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" wire:click="$set('rating', {{ $i }})"
                                        class="text-2xl {{ $i <= $rating ? 'text-amber-400' : 'text-gray-300' }} hover:text-amber-400 transition">
                                    ★
                                </button>
                            @endfor
                        </div>
                    </div>
                    <div>
                        <textarea wire:model="ulasan" rows="3" placeholder="Tulis ulasan Anda..."
                                  class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('ulasan') border-red-500 @enderror">
                        </textarea>
                        @error('ulasan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                        Kirim Ulasan
                    </button>
                </form>
                @endif
                @endauth

                <div class="mt-4 space-y-4">
                    @forelse($buku->ulasan as $ulasan)
                        <div class="border-t border-gray-100 pt-4">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900">{{ $ulasan->user->NamaLengkap ?? $ulasan->user->name }}</p>
                                <span class="text-sm text-amber-400">
                                    @for($i = 1; $i <= 5; $i++){{ $i <= $ulasan->Rating ? '★' : '☆' }}@endfor
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-gray-600">{{ $ulasan->Ulasan }}</p>
                            <p class="mt-1 text-xs text-gray-400">{{ $ulasan->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 mt-4">Belum ada ulasan untuk buku ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>