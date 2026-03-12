{{-- resources/views/buku/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Edit Buku</h2>
    </x-slot>
    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <livewire:buku.buku-form :buku="$buku" />
        </div>
    </div>
</x-app-layout>