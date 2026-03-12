{{-- resources/views/buku/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('buku.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">← Katalog</a>
            <span class="text-gray-400">/</span>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Detail Buku</h2>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <livewire:buku.buku-detail :buku="$buku" />
        </div>
    </div>
</x-app-layout>