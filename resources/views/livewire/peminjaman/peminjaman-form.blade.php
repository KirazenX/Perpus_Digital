<div class="mx-auto max-w-lg">
    <div class="rounded-xl bg-white p-6 shadow ring-1 ring-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Form Peminjaman Buku</h2>
        <p class="mt-1 text-sm text-gray-500">Silakan isi tanggal peminjaman dan pengembalian.</p>

        {{-- Book Info --}}
        <div class="mt-4 flex gap-4 rounded-lg bg-indigo-50 p-4">
            @if($buku->CoverImage)
                <img src="{{ asset('storage/' . $buku->CoverImage) }}" alt="{{ $buku->Judul }}" class="h-20 w-14 rounded-lg object-cover shadow"/>
            @else
                <div class="flex h-20 w-14 items-center justify-center rounded-lg bg-indigo-100">
                    <svg class="h-8 w-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                </div>
            @endif
            <div>
                <p class="font-semibold text-gray-900">{{ $buku->Judul }}</p>
                <p class="text-sm text-gray-500">{{ $buku->Penulis }}</p>
                <p class="mt-1 text-xs {{ $buku->isAvailable() ? 'text-green-600' : 'text-red-600' }}">
                    Stok: {{ $buku->StokTersedia }}/{{ $buku->StokTotal }}
                </p>
            </div>
        </div>

        @if(session('error'))
            <div class="mt-4 rounded-lg bg-red-50 p-3 text-sm text-red-700">{{ session('error') }}</div>
        @endif

        <form wire:submit="submit" class="mt-5 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Tanggal Peminjaman <span class="text-red-500">*</span>
                </label>
                <input wire:model="TanggalPeminjaman" type="date"
                       min="{{ now()->format('Y-m-d') }}"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('TanggalPeminjaman') border-red-500 @enderror"/>
                @error('TanggalPeminjaman')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">
                    Tanggal Pengembalian <span class="text-red-500">*</span>
                </label>
                <input wire:model="TanggalPengembalian" type="date"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('TanggalPengembalian') border-red-500 @enderror"/>
                @error('TanggalPengembalian')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                <p class="mt-1 text-xs text-gray-500">Maksimal peminjaman 14 hari.</p>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('buku.show', $buku->BukuID) }}"
                   class="flex-1 rounded-lg bg-white py-2.5 text-center text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                    <div wire:loading wire:target="submit" class="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></div>
                    Ajukan Peminjaman
                </button>
            </div>
        </form>
    </div>
</div>