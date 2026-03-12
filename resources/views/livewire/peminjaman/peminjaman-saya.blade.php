<div>
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex justify-end">
        <select wire:model.live="statusFilter"
                class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <option value="">Semua Status</option>
            @foreach($statusOptions as $status)
                <option value="{{ $status->value }}">{{ $status->label() }}</option>
            @endforeach
        </select>
    </div>

    @if($peminjamanList->isEmpty())
        <div class="rounded-xl bg-gray-50 px-6 py-14 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900">Belum ada peminjaman</h3>
            <p class="mt-1 text-sm text-gray-500">Kunjungi katalog dan mulai pinjam buku.</p>
            <a href="{{ route('buku.index') }}" class="mt-4 inline-block rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
                Lihat Katalog
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($peminjamanList as $p)
                <div class="flex gap-4 rounded-xl bg-white p-4 shadow ring-1 ring-gray-200">
                    <a href="{{ route('buku.show', $p->buku->BukuID) }}">
                        @if($p->buku->CoverImage)
                            <img src="{{ asset('storage/' . $p->buku->CoverImage) }}" alt="{{ $p->buku->Judul }}"
                                 class="h-20 w-14 rounded-lg object-cover shadow"/>
                        @else
                            <div class="flex h-20 w-14 items-center justify-center rounded-lg bg-indigo-50">
                                <svg class="h-8 w-8 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                                </svg>
                            </div>
                        @endif
                    </a>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <a href="{{ route('buku.show', $p->buku->BukuID) }}"
                                   class="text-sm font-semibold text-gray-900 hover:text-indigo-600">
                                    {{ $p->buku->Judul }}
                                </a>
                                <p class="text-xs text-gray-500">{{ $p->buku->Penulis }}</p>
                            </div>
                            <span class="shrink-0 rounded-full px-2.5 py-0.5 text-xs font-medium {{ $p->StatusPeminjaman->badge() }}">
                                {{ $p->StatusPeminjaman->label() }}
                            </span>
                        </div>
                        <div class="mt-2 grid grid-cols-2 gap-x-4 gap-y-1 text-xs text-gray-500">
                            <span>Dipinjam: {{ $p->TanggalPeminjaman->format('d M Y') }}</span>
                            <span>Kembali: {{ $p->TanggalPengembalian->format('d M Y') }}</span>
                            @if($p->TanggalDikembalikan)
                                <span class="text-green-600">Dikembalikan: {{ $p->TanggalDikembalikan->format('d M Y') }}</span>
                            @endif
                            @if($p->StatusPeminjaman->value === 'dipinjam' && now()->gt($p->TanggalPengembalian))
                                <span class="text-red-600 font-semibold">
                                    Terlambat {{ now()->diffInDays($p->TanggalPengembalian) }} hari!
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $peminjamanList->links() }}</div>
    @endif
</div>