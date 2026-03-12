<div>
    <div class="mb-6">
        <flux:button icon="arrow-left" variant="ghost" x-on:click="history.back()">Kembali</flux:button>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-6">{{ session('success') }}</flux:callout>
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
                    <div class="flex aspect-[3/4] items-center justify-center bg-linear-to-br from-indigo-50 to-purple-50 dark:from-indigo-950/30 dark:to-purple-950/30">
                        <flux:icon name="book-open" class="size-24 text-indigo-200 dark:text-indigo-900" />
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            <div class="mt-6 space-y-3">
                @auth
                    @if(auth()->user()->isPeminjam())
                        @if($buku->isAvailable())
                            <flux:button href="{{ route('peminjaman.create', $buku->BukuID) }}" variant="primary" size="lg" class="w-full" wire:navigate>
                                Pinjam Buku
                            </flux:button>
                        @else
                            <flux:button disabled variant="filled" size="lg" class="w-full">
                                Stok Habis
                            </flux:button>
                        @endif
                    @endif
                    <flux:button wire:click="toggleKoleksi"
                            variant="{{ $isInKoleksi ? 'filled' : 'outline' }}"
                            size="lg"
                            class="w-full"
                            :icon="$isInKoleksi ? 'star' : 'star'"
                    >
                        {{ $isInKoleksi ? 'Hapus dari Koleksi' : 'Simpan ke Koleksi' }}
                    </flux:button>
                @else
                    <flux:button href="{{ route('login') }}" variant="primary" size="lg" class="w-full">
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
                            {{ $buku->StokTersedia }} / {{ $buku->StokTotal }} Unit
                        </flux:badge>
                    </div>
                    <div class="space-y-1">
                        <flux:text size="xs" class="uppercase font-bold tracking-wider text-zinc-400 dark:text-zinc-500">Rating</flux:text>
                        <div class="flex items-center gap-1">
                            <flux:icon name="star" variant="solid" class="size-4 text-amber-400" />
                            <flux:text class="font-bold text-zinc-900 dark:text-zinc-200">{{ $buku->averageRating() }}</flux:text>
                            <flux:text size="xs" class="text-zinc-400">({{ $buku->ulasan->count() }} ulasan)</flux:text>
                        </div>
                    </div>
                </div>

                @if($buku->Deskripsi)
                <div class="space-y-2">
                    <flux:heading size="sm">Deskripsi</flux:heading>
                    <flux:text class="leading-relaxed text-zinc-600 dark:text-zinc-400">{{ $buku->Deskripsi }}</flux:text>
                </div>
                @endif
            </div>

            <flux:separator />

            {{-- Ulasan --}}
            <div class="space-y-6">
                <flux:heading size="lg">Ulasan Pembaca</flux:heading>

                @auth
                @if(auth()->user()->isPeminjam())
                <div class="rounded-xl bg-zinc-50 dark:bg-zinc-900/50 p-6 border border-zinc-200 dark:border-zinc-800">
                    <form wire:submit="submitUlasan" class="space-y-4">
                        <flux:field>
                            <flux:label>Rating Anda</flux:label>
                            <div class="mt-1 flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button" wire:click="$set('rating', {{ $i }})"
                                            class="transition-transform active:scale-95">
                                        <flux:icon name="star" :variant="$i <= $rating ? 'solid' : 'outline'" 
                                                   class="size-8 {{ $i <= $rating ? 'text-amber-400' : 'text-zinc-300 dark:text-zinc-600' }} hover:text-amber-400 transition" />
                                    </button>
                                @endfor
                            </div>
                        </flux:field>
                        
                        <flux:textarea wire:model="ulasan" rows="3" placeholder="Bagikan pendapat Anda tentang buku ini..." label="Ulasan Anda" />
                        
                        <div class="flex justify-end">
                            <flux:button type="submit" variant="primary">Kirim Ulasan</flux:button>
                        </div>
                    </form>
                </div>
                @endif
                @endauth

                <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @forelse($buku->ulasan as $ulasan)
                        <div class="py-6 space-y-2" wire:key="ulasan-{{ $ulasan->UlasanID }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <flux:avatar :name="$ulasan->user->name" size="xs" />
                                    <flux:text class="font-bold text-zinc-900 dark:text-zinc-200">{{ $ulasan->user->NamaLengkap ?? $ulasan->user->name }}</flux:text>
                                </div>
                                <div class="flex gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <flux:icon name="star" variant="solid" class="size-3 {{ $i <= $ulasan->Rating ? 'text-amber-400' : 'text-zinc-200 dark:text-zinc-800' }}" />
                                    @endfor
                                </div>
                            </div>
                            <flux:text class="text-zinc-600 dark:text-zinc-400">{{ $ulasan->Ulasan }}</flux:text>
                            <flux:text size="xs" class="text-zinc-400">{{ $ulasan->created_at->diffForHumans() }}</flux:text>
                        </div>
                    @empty
                        <div class="py-10 text-center">
                            <flux:text class="text-zinc-500 italic">Belum ada ulasan untuk buku ini. Jadilah yang pertama memberikan ulasan!</flux:text>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>