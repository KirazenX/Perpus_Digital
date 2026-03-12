{{-- resources/views/admin/pengguna.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Manajemen Pengguna</h2>
    </x-slot>
    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <livewire:admin.manajemen-pengguna />
        </div>
    </div>
</x-app-layout>