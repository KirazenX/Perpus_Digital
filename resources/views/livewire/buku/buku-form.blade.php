<div class="mx-auto max-w-3xl">
    <form wire:submit="save" class="space-y-6">
        <div class="rounded-xl bg-white p-6 shadow ring-1 ring-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">
                {{ $isEdit ? 'Edit Buku' : 'Tambah Buku Baru' }}
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                {{ $isEdit ? 'Perbarui informasi buku.' : 'Isi form berikut untuk menambahkan buku baru ke perpustakaan.' }}
            </p>

            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Judul Buku <span class="text-red-500">*</span></label>
                    <input wire:model="Judul" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('Judul') border-red-500 @enderror"/>
                    @error('Judul')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Penulis <span class="text-red-500">*</span></label>
                    <input wire:model="Penulis" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('Penulis') border-red-500 @enderror"/>
                    @error('Penulis')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Penerbit <span class="text-red-500">*</span></label>
                    <input wire:model="Penerbit" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('Penerbit') border-red-500 @enderror"/>
                    @error('Penerbit')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahun Terbit <span class="text-red-500">*</span></label>
                    <input wire:model="TahunTerbit" type="number" min="1000" max="{{ date('Y') + 1 }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('TahunTerbit') border-red-500 @enderror"/>
                    @error('TahunTerbit')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">ISBN</label>
                    <input wire:model="ISBN" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('ISBN') border-red-500 @enderror"/>
                    @error('ISBN')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Stok Total <span class="text-red-500">*</span></label>
                    <input wire:model="StokTotal" type="number" min="1" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('StokTotal') border-red-500 @enderror"/>
                    @error('StokTotal')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea wire:model="Deskripsi" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Cover Buku</label>
                    <input wire:model="CoverImage" type="file" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-full file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-indigo-700 hover:file:bg-indigo-100"/>
                    @error('CoverImage')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    <div wire:loading wire:target="CoverImage" class="mt-1 text-xs text-indigo-600">Mengupload...</div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <div class="mt-2 flex flex-wrap gap-3">
                        @foreach($kategoriList as $kat)
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm hover:bg-gray-50 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50">
                                <input
                                    type="checkbox"
                                    wire:model="selectedKategori"
                                    value="{{ $kat->KategoriID }}"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                />
                                {{ $kat->NamaKategori }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('buku.index') }}" class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <div wire:loading wire:target="save" class="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></div>
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Buku' }}
            </button>
        </div>
    </form>
</div>