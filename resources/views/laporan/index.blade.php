<x-layouts.app :title="__('Laporan Peminjaman')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="mb-2">
            <flux:heading size="xl" level="1">Laporan Peminjaman</flux:heading>
            <flux:subheading>Statistik dan data peminjaman berdasarkan periode</flux:subheading>
        </div>

        <livewire:laporan.laporan-peminjaman />
    </div>
</x-layouts.app>