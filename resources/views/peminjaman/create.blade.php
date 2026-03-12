<x-layouts.app :title="__('Form Peminjaman')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="mb-2">
            <flux:heading size="xl" level="1">Form Peminjaman</flux:heading>
            <flux:subheading>Ajukan permintaan peminjaman buku</flux:subheading>
        </div>

        <livewire:peminjaman.peminjaman-form :buku="$buku" />
    </div>
</x-layouts.app>