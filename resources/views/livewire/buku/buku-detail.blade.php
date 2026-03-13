<div>
    <div class="mb-6">
        <flux:button icon="arrow-left" variant="ghost" x-on:click="history.back()">Kembali</flux:button>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-6">{{ session('success') }}</flux:callout>
    @endif
    @if(session('info'))
        <flux:callout variant="info" class="mb-6">{{ session('info') }}</flux:callout>
    @endif
    @if(session('error'))
        <flux:callout variant="danger" class="mb-6">{{ session('error') }}</flux:callout>
    @endif

    <div class="grid grid-cols-1 gap-10 lg:grid-cols-3">
        {{-- Cover --}}
        <div class="lg:col-span-1">
            <div class="overflow-hidden rounded-2xl bg-zinc-100 dark:bg-zinc-800 shadow-md ring-1 ring-zinc-200 dark:ring-zinc-700">
                @if($buku->CoverImage)
                    <img src="{{ asset('storage/' . $buku->CoverImage) }}" alt="{{ $buku->Judul }}" class="w-full object-cover"/>
                @else
                    <div class="flex aspect-[3/4] items-center justify-center bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-950/30 dark:to-purple-950/30">
                        <flux:icon name="book-open" class="size-24 text-indigo-200 dark:text-indigo-900" />
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="mt-6 space-y-3">
                @auth
                    @if(auth()->user()->isPeminjam())
                        {{-- Tombol Pinjam --}}
                        @if($buku->isAvailable())
                            <flux:button href="{{ route('peminjaman.create', $buku->BukuID) }}" variant="primary" size="base" class="w-full" wire:navigate>
                                Pinjam Buku
                            </flux:button>
                        @else
                            <flux:button disabled variant="filled" size="base" class="w-full">
                                Stok Habis
                            </flux:button>
                        @endif

                        {{-- Tombol Koleksi: hanya untuk peminjam --}}
                        <flux:button
                            wire:click="toggleKoleksi"
                            variant="{{ $isInKoleksi ? 'filled' : 'outline' }}"
                            size="base"
                            class="w-full"
                            icon="{{ $isInKoleksi ? 'star' : 'star' }}"
                        >
                            {{ $isInKoleksi ? 'Hapus dari Koleksi' : 'Simpan ke Koleksi' }}
                        </flux:button>
                    @endif
                @else
                    <flux:button href="{{ route('login') }}" variant="primary" size="base" class="w-full" wire:navigate>
                        Login untuk Meminjam
                    </flux:button>
                @endauth
            </div>
        </div>

        {{-- Detail --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="space-y-4">
                <div class="flex flex-wrap gap-2">
                    @foreach($buku->kategori as $kat)
                        <flux:badge variant="neutral" size="sm" class="uppercase tracking-wide font-semibold">
                            {{ $kat->NamaKategori }}
                        </flux:badge>
                    @endforeach
                </div>

                <flux:heading size="xl" class="text-3xl font-extrabold">{{ $buku->Judul }}</flux:heading>
                <flux:text size="lg" class="text-zinc-600 dark:text-zinc-400">{{ $buku->Penulis }}</flux:text>

                <div class="mt-8 grid grid-cols-2 gap-6 sm:grid-cols-4 border-y border-zinc-100 dark:border-zinc-800 py-6">
                    <div class="space-y-1">
                        <flux:text size="xs" class="uppercase font-bold tracking-wider text-zinc-400 dark:text-zinc-500">Penerbit</flux:text>
                        <flux:text class="font-medium text-zinc-900 dark:text-zinc-200">{{ $buku->Penerbit }}</flux:text>
                    </div>
                    <div class="space-y-1">
                        <flux:text size="xs" class="uppercase font-bold tracking-wider text-zinc-400 dark:text-zinc-500">Tahun Terbit</flux:text>
                        <flux:text class="font-medium text-zinc-900 dark:text-zinc-200">{{ $buku->TahunTerbit }}</flux:text>
                    </div>
                    <div class="space-y-1">
                        <flux:text size="xs" class="uppercase font-bold tracking-wider text-zinc-400 dark:text-zinc-500">Ketersediaan</flux:text>
                        <flux:badge :variant="$buku->isAvailable() ? 'success' : 'danger'" size="sm">
                            {{ $buku->isAvailable() ? 'Tersedia' : 'Tidak Tersedia' }}
                        </flux:badge>
                    </div>
                    <div class="space-y-1">
                        <flux:text size="xs" class="uppercase font-bold tracking-wider text-zinc-400 dark:text-zinc-500">Rating</flux:text>
                        <div class="flex items-center gap-1">
                            <flux:icon name="star" class="size-4 text-yellow-400" variant="solid" />
                            <flux:text class="font-medium text-zinc-900 dark:text-zinc-200">{{ $buku->averageRating() ?: '—' }}</flux:text>
                        </div>
                    </div>
                </div>

                @if($buku->Deskripsi)
                    <div class="space-y-2">
                        <flux:text size="sm" class="uppercase font-bold tracking-wider text-zinc-400 dark:text-zinc-500">Deskripsi</flux:text>
                        <flux:text class="text-zinc-700 dark:text-zinc-300 leading-relaxed">{{ $buku->Deskripsi }}</flux:text>
                    </div>
                @endif
            </div>

            {{-- Ulasan --}}
            <div class="space-y-6">
                <flux:heading size="lg">Ulasan Pembaca</flux:heading>

                @auth
                    @if(auth()->user()->isPeminjam())
                        <div class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900 p-5 space-y-4">
                            <flux:heading size="sm">Tulis Ulasan</flux:heading>
                            <div class="flex items-center gap-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <button wire:click="$set('rating', {{ $i }})" class="focus:outline-none">
                                        <flux:icon name="star" class="size-6 transition {{ $i <= $rating ? 'text-yellow-400' : 'text-zinc-300 dark:text-zinc-600' }}" variant="solid" />
                                    </button>
                                @endfor
                            </div>
                            <flux:textarea wire:model="ulasan" rows="3" placeholder="Bagikan pendapat Anda tentang buku ini..." />
                            @error('ulasan') <flux:text class="text-red-500 text-sm">{{ $message }}</flux:text> @enderror
                            <flux:button wire:click="submitUlasan" variant="primary" size="sm">Kirim Ulasan</flux:button>
                        </div>
                    @endif
                @endauth

                @forelse($buku->ulasan as $u)
                    <div wire:key="ulasan-{{ $u->UlasanID }}" class="flex gap-4 pb-6 border-b border-zinc-100 dark:border-zinc-800 last:border-0">
                        <flux:avatar :name="$u->user->name" size="sm" class="shrink-0" />
                        <div class="flex-1 space-y-1">
                            <div class="flex items-center justify-between">
                                <flux:text class="font-semibold text-zinc-900 dark:text-zinc-100">{{ $u->user->NamaLengkap ?? $u->user->name }}</flux:text>
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <flux:icon name="star" class="size-3.5 {{ $i <= $u->Rating ? 'text-yellow-400' : 'text-zinc-300 dark:text-zinc-600' }}" variant="solid" />
                                    @endfor
                                </div>
                            </div>
                            <flux:text size="sm" class="text-zinc-500">{{ $u->created_at->diffForHumans() }}</flux:text>
                            <flux:text class="text-zinc-700 dark:text-zinc-300 mt-2">{{ $u->Ulasan }}</flux:text>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <flux:icon name="chat-bubble-left-ellipsis" class="mx-auto size-10 text-zinc-300 dark:text-zinc-700" />
                        <flux:text class="mt-2 text-zinc-400">Belum ada ulasan untuk buku ini.</flux:text>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
