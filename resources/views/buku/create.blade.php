<x-layouts.app :title="__('Tambah Buku')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="mb-2">
            <flux:heading size="xl" level="1">Tambah Buku Baru</flux:heading>
            <flux:subheading>Isi form berikut untuk menambahkan buku ke perpustakaan</flux:subheading>
        </div>

        <livewire:buku.buku-form />
    </div>
</x-layouts.app>