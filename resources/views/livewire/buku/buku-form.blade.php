<div class="mx-auto max-w-3xl">
    <form wire:submit="save" class="space-y-8">
        <div class="rounded-2xl bg-white dark:bg-zinc-900 p-8 shadow-sm border border-zinc-200 dark:border-zinc-800">
            <div class="mb-8">
                <flux:heading size="xl">{{ $isEdit ? 'Edit Buku' : 'Tambah Buku Baru' }}</flux:heading>
                <flux:text class="mt-1">
                    {{ $isEdit ? 'Perbarui informasi detail buku yang sudah ada.' : 'Isi form berikut untuk menambahkan koleksi buku baru ke sistem.' }}
                </flux:text>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <flux:input wire:model="Judul" label="Judul Buku" required placeholder="Contoh: Laskar Pelangi" />
                </div>

                <flux:input wire:model="Penulis" label="Penulis" required placeholder="Nama penulis" />
                <flux:input wire:model="Penerbit" label="Penerbit" required placeholder="Nama penerbit" />

                <flux:input wire:model="TahunTerbit" type="number" label="Tahun Terbit" required min="1000" max="{{ date('Y') + 1 }}" />
                <flux:input wire:model="StokTotal" type="number" label="Stok Total" required min="1" />

                <div class="sm:col-span-2">
                    <flux:textarea wire:model="Deskripsi" label="Deskripsi" rows="4" placeholder="Ringkasan atau sinopsis buku..." />
                </div>

                <div class="sm:col-span-2">
                    <flux:field>
                        <flux:label>Cover Buku</flux:label>
                        <div class="mt-2 flex items-center gap-4">
                            @if($CoverImage && is_object($CoverImage))
                                <img src="{{ $CoverImage->temporaryUrl() }}" class="size-24 rounded-lg object-cover ring-1 ring-zinc-200 dark:ring-zinc-700" />
                            @elseif($isEdit && $buku->CoverImage)
                                <img src="{{ asset('storage/' . $buku->CoverImage) }}" class="size-24 rounded-lg object-cover ring-1 ring-zinc-200 dark:ring-zinc-700" />
                            @else
                                <div class="size-24 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center border border-dashed border-zinc-300 dark:border-zinc-700">
                                    <flux:icon name="photo" class="size-8 text-zinc-400" />
                                </div>
                            @endif
                            <div class="flex-1">
                                <input wire:model="CoverImage" type="file" accept="image/*" class="block w-full text-sm text-zinc-500 file:mr-4 file:rounded-full file:border-0 file:bg-indigo-50 dark:file:bg-indigo-950/30 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 dark:file:text-indigo-400 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-950/50 cursor-pointer"/>
                                <flux:error name="CoverImage" />
                                <div wire:loading wire:target="CoverImage" class="mt-2 text-xs text-indigo-600 font-medium">Mengupload...</div>
                            </div>
                        </div>
                    </flux:field>
                </div>

                <div class="sm:col-span-2">
                    <flux:field>
                        <flux:label>Kategori</flux:label>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($kategoriList as $kat)
                                <label wire:key="kat-{{ $kat->KategoriID }}" class="flex cursor-pointer items-center gap-2 rounded-xl border border-zinc-200 dark:border-zinc-800 px-4 py-2 text-sm transition hover:bg-zinc-50 dark:hover:bg-zinc-800/50 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 dark:has-[:checked]:bg-indigo-950/20">
                                    <input
                                        type="checkbox"
                                        wire:model="selectedKategori"
                                        value="{{ $kat->KategoriID }}"
                                        class="rounded-sm border-zinc-300 text-indigo-600 focus:ring-indigo-500 dark:bg-zinc-950 dark:border-zinc-700"
                                    />
                                    <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ $kat->NamaKategori }}</span>
                                </label>
                            @endforeach
                        </div>
                        <flux:error name="selectedKategori" />
                    </flux:field>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <flux:button href="{{ route('buku.index') }}" variant="ghost" wire:navigate>Batal</flux:button>
            <flux:button type="submit" variant="primary" class="px-8">
                <div wire:loading wire:target="save" class="mr-2">
                    <flux:icon name="arrow-path" class="size-4 animate-spin" />
                </div>
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Buku' }}
            </flux:button>
        </div>
    </form>
</div>