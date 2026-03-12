<x-layouts::app :title="__('Dashboard')">
    <div class="space-y-8">
        <div class="flex items-center justify-between">
            <flux:heading size="xl" level="1">Selamat Datang, {{ auth()->user()->name }}!</flux:heading>
            <flux:text class="text-zinc-500">{{ now()->translatedFormat('l, d F Y') }}</flux:text>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="rounded-xl bg-indigo-50 dark:bg-indigo-950/30 p-3 text-indigo-600 dark:text-indigo-400">
                        <flux:icon name="book-open" class="size-6" />
                    </div>
                    <div>
                        <flux:text size="sm" class="font-medium text-zinc-500">Koleksi Buku</flux:text>
                        <flux:heading size="lg" class="font-bold">{{\App\Models\Buku::count()}}</flux:heading>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="rounded-xl bg-emerald-50 dark:bg-emerald-950/30 p-3 text-emerald-600 dark:text-emerald-400">
                        <flux:icon name="clipboard-document-check" class="size-6" />
                    </div>
                    <div>
                        <flux:text size="sm" class="font-medium text-zinc-500">Total Peminjaman</flux:text>
                        <flux:heading size="lg" class="font-bold">{{\App\Models\Peminjaman::count()}}</flux:heading>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="rounded-xl bg-amber-50 dark:bg-amber-950/30 p-3 text-amber-600 dark:text-amber-400">
                        <flux:icon name="star" class="size-6" />
                    </div>
                    <div>
                        <flux:text size="sm" class="font-medium text-zinc-500">Total Ulasan</flux:text>
                        <flux:heading size="lg" class="font-bold">{{\App\Models\UlasanBuku::count()}}</flux:heading>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="space-y-4">
                <flux:heading size="lg">Buku Terpopuler</flux:heading>
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden shadow-sm">
                    @php
                        $topBooks = \App\Models\Buku::withCount('ulasan')->orderBy('ulasan_count', 'desc')->take(5)->get();
                    @endphp
                    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse($topBooks as $buku)
                            <a href="{{ route('buku.show', $buku->BukuID) }}" class="flex items-center gap-4 p-4 hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition">
                                <div class="size-12 shrink-0 overflow-hidden rounded-lg bg-zinc-100 dark:bg-zinc-800">
                                    @if($buku->CoverImage)
                                        <img src="{{ asset('storage/' . $buku->CoverImage) }}" class="h-full w-full object-cover" />
                                    @else
                                        <div class="flex h-full w-full items-center justify-center">
                                            <flux:icon name="book-open" class="size-6 text-zinc-300 dark:text-zinc-600" />
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <flux:text class="font-bold truncate dark:text-zinc-200">{{ $buku->Judul }}</flux:text>
                                    <flux:text size="sm" class="text-zinc-500 dark:text-zinc-400">{{ $buku->Penulis }}</flux:text>
                                </div>
                                <div class="flex items-center gap-1 text-amber-500">
                                    <flux:icon name="star" variant="solid" class="size-4" />
                                    <span class="text-sm font-bold">{{ $buku->averageRating() }}</span>
                                </div>
                            </a>
                        @empty
                            <div class="p-8 text-center text-zinc-500">Belum ada data buku.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <flux:heading size="lg">Aktivitas Terkini</flux:heading>
                <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden shadow-sm">
                    @php
                        $recentLoans = \App\Models\Peminjaman::with(['user', 'buku'])->latest()->take(5)->get();
                    @endphp
                    <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse($recentLoans as $pinjam)
                            <div class="p-4 flex items-start gap-4">
                                <flux:avatar :name="$pinjam->user->name" size="sm" class="mt-0.5" />
                                <div class="flex-1 min-w-0">
                                    <flux:text class="text-sm dark:text-zinc-300">
                                        <span class="font-bold">{{ $pinjam->user->name }}</span> 
                                        meminjam 
                                        <span class="font-bold">{{ $pinjam->buku->Judul }}</span>
                                    </flux:text>
                                    <flux:text size="xs" class="mt-1 text-zinc-400">{{ $pinjam->created_at->diffForHumans() }}</flux:text>
                                </div>
                                <flux:badge :variant="$pinjam->StatusPeminjaman->color()" size="sm" inset="top bottom">
                                    {{ $pinjam->StatusPeminjaman->label() }}
                                </flux:badge>
                            </div>
                        @empty
                            <div class="p-8 text-center text-zinc-500">Belum ada aktivitas peminjaman.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
