<x-layouts.app :title="__('Katalog Buku')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="mb-2">
            <flux:heading size="xl" level="1">Katalog Buku</flux:heading>
            <flux:subheading>Temukan dan pinjam buku yang Anda inginkan</flux:subheading>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-green-50 p-4 text-sm text-green-700 dark:bg-green-900/20 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        <livewire:buku.buku-index />
    </div>
</x-layouts.app>