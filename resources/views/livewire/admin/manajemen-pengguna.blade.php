<div>
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <flux:heading size="xl">Manajemen Pengguna</flux:heading>
        <flux:button wire:click="openCreate" variant="primary" icon="plus">
            Tambah Pengguna
        </flux:button>
    </div>

    @if(session('success'))
        <flux:callout variant="success" class="mb-6">{{ session('success') }}</flux:callout>
    @endif
    @if(session('error'))
        <flux:callout variant="danger" class="mb-6">{{ session('error') }}</flux:callout>
    @endif

    <div class="mb-6 flex flex-col gap-4 sm:flex-row">
        <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="Cari pengguna..." class="flex-1" />
        <flux:select wire:model.live="roleFilter" placeholder="Semua Role" class="sm:w-64">
            <flux:select.option value="">Semua Role</flux:select.option>
            <flux:select.option value="administrator">Administrator</flux:select.option>
            <flux:select.option value="petugas">Petugas</flux:select.option>
            <flux:select.option value="peminjam">Peminjam</flux:select.option>
        </flux:select>
    </div>

    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden shadow-sm">
        <flux:table>
            <flux:table.columns>
                <flux:table.column>Pengguna</flux:table.column>
                <flux:table.column>Role</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Bergabung</flux:table.column>
                <flux:table.column align="right">Aksi</flux:table.column>
            </flux:table.columns>

            <flux:table.rows>
                @forelse($users as $user)
                    <flux:table.row wire:key="user-{{ $user->id }}">
                        <flux:table.cell>
                            <div class="flex items-center gap-3">
                                <flux:avatar :name="$user->name" size="xs" />
                                <div class="flex flex-col">
                                    <span class="font-bold text-zinc-900 dark:text-zinc-100">{{ $user->NamaLengkap ?? $user->name }}</span>
                                    <span class="text-xs text-zinc-500">{{ $user->email }}</span>
                                </div>
                            </div>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" :variant="$user->role === 'administrator' ? 'primary' : ($user->role === 'petugas' ? 'brand' : 'neutral')">
                                {{ ucfirst($user->role) }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:badge size="sm" :variant="$user->is_active ? 'success' : 'danger'" inset="top bottom">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </flux:badge>
                        </flux:table.cell>
                        <flux:table.cell class="text-xs text-zinc-500">
                            {{ $user->created_at->translatedFormat('d M Y') }}
                        </flux:table.cell>
                        <flux:table.cell align="right">
                            <div class="flex justify-end gap-2">
                                <flux:button wire:click="openEdit({{ $user->id }})" variant="ghost" size="sm" icon="pencil" />
                                @if($user->id !== auth()->id())
                                    <flux:button wire:click="toggleActive({{ $user->id }})" variant="ghost" size="sm" 
                                                 :icon="$user->is_active ? 'no-symbol' : 'check-circle'" 
                                                 :class="$user->is_active ? 'text-red-600' : 'text-emerald-600'" />
                                @endif
                            </div>
                        </flux:table.cell>
                    </flux:table.row>
                @empty
                    <flux:table.row>
                        <flux:table.cell colspan="5" class="py-12 text-center text-zinc-500">
                            Tidak ada pengguna yang ditemukan.
                        </flux:table.cell>
                    </flux:table.row>
                @endforelse
            </flux:table.rows>
        </flux:table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>

    {{-- Form Modal --}}
    <flux:modal wire:model="showForm" class="md:w-[500px]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $editingId ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}</flux:heading>
                <flux:text>Silakan isi informasi pengguna di bawah ini.</flux:text>
            </div>

            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <flux:input wire:model="name" label="Username" required />
                    <flux:input wire:model="NamaLengkap" label="Nama Lengkap" required />
                </div>

                <flux:input wire:model="email" type="email" label="Email" required />

                <flux:input wire:model="password" type="password" 
                            :label="'Password' . ($editingId ? ' (kosongkan jika tidak diubah)' : '')" 
                            :required="!$editingId" />

                <flux:select wire:model="role" label="Role" required>
                    <flux:select.option value="peminjam">Peminjam</flux:select.option>
                    <flux:select.option value="petugas">Petugas</flux:select.option>
                    <flux:select.option value="administrator">Administrator</flux:select.option>
                </flux:select>

                <flux:textarea wire:model="Alamat" label="Alamat" rows="2" />

                <div class="flex gap-3 justify-end pt-4">
                    <flux:modal.close><flux:button variant="ghost">Batal</flux:button></flux:modal.close>
                    <flux:button type="submit" variant="primary">
                        <div wire:loading wire:target="save" class="mr-2">
                            <flux:icon name="arrow-path" class="size-4 animate-spin" />
                        </div>
                        {{ $editingId ? 'Simpan Perubahan' : 'Tambahkan' }}
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>
</div>