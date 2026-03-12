<x-layouts.app :title="__('Manajemen Pengguna')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">
        <div class="mb-2">
            <flux:heading size="xl" level="1">Manajemen Pengguna</flux:heading>
            <flux:subheading>Kelola akun administrator, petugas, dan peminjam</flux:subheading>
        </div>

        <livewire:admin.manajemen-pengguna />
    </div>
</x-layouts.app>