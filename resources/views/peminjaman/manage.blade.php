<x-layouts.app :title="__('Kelola Peminjaman')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="mb-2">
            <flux:heading size="xl" level="1">Kelola Peminjaman</flux:heading>
            <flux:subheading>Setujui, tolak, atau konfirmasi pengembalian buku</flux:subheading>
        </div>

        <livewire:peminjaman.peminjaman-manage />
    </div>
</x-layouts.app>
