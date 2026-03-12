<x-layouts.app :title="__('Edit Buku')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="mb-2">
            <flux:heading size="xl" level="1">Edit Buku</flux:heading>
            <flux:subheading>Perbarui informasi buku</flux:subheading>
        </div>

        <livewire:buku.buku-form :buku="$buku" />
    </div>
</x-layouts.app>