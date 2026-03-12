<x-layouts.app :title="$buku->Judul">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="mb-2 flex items-center gap-2 text-sm">
            <a href="{{ route('buku.index') }}" class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200" wire:navigate>
                ← Katalog
            </a>
        </div>

        <livewire:buku.buku-detail :buku="$buku" />
    </div>
</x-layouts.app>