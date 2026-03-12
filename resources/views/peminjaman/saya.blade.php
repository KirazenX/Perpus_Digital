<x-layouts.app :title="__('Peminjaman Saya')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="mb-2">
            <flux:heading size="xl" level="1">Peminjaman Saya</flux:heading>
            <flux:subheading>Riwayat dan status peminjaman buku Anda</flux:subheading>
        </div>

        <livewire:peminjaman.peminjaman-saya />
    </div>
</x-layouts.app>