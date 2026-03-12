<div>
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    {{-- Form Modal --}}
    @if($showForm)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-black/50 flex items-center justify-center p-4">
        <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-2xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ $editingId ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
                </h3>
                <button wire:click="$set('showForm', false)" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                    </svg>
                </button>
            </div>

            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Username <span class="text-red-500">*</span></label>
                        <input wire:model="name" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-500 @enderror"/>
                        @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input wire:model="NamaLengkap" type="text" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('NamaLengkap') border-red-500 @enderror"/>
                        @error('NamaLengkap')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                    <input wire:model="email" type="email" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('email') border-red-500 @enderror"/>
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">
                        Password {{ $editingId ? '(kosongkan jika tidak diubah)' : '' }}
                        @if(!$editingId)<span class="text-red-500">*</span>@endif
                    </label>
                    <input wire:model="password" type="password"
                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('password') border-red-500 @enderror"/>
                    @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Role <span class="text-red-500">*</span></label>
                    <select wire:model="role" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="petugas">Petugas</option>
                        <option value="administrator">Administrator</option>
                        <option value="peminjam">Peminjam</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-700">Alamat</label>
                    <textarea wire:model="Alamat" rows="2" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" wire:click="$set('showForm', false)"
                            class="flex-1 rounded-lg bg-white py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        <div wire:loading wire:target="save" class="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"></div>
                        {{ $editingId ? 'Simpan' : 'Tambahkan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Toolbar --}}
    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-1 gap-3">
            <input wire:model.live.debounce.300ms="search" type="text"
                   placeholder="Cari pengguna..."
                   class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
            <select wire:model.live="roleFilter"
                    class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <option value="">Semua Role</option>
                <option value="administrator">Administrator</option>
                <option value="petugas">Petugas</option>
                <option value="peminjam">Peminjam</option>
            </select>
        </div>
        <button wire:click="openCreate"
                class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500">
            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"/>
            </svg>
            Tambah Petugas
        </button>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-xl bg-white shadow ring-1 ring-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Pengguna</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Role</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Bergabung</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="text-sm font-medium text-gray-900">{{ $user->NamaLengkap ?? $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                {{ $user->role === 'administrator' ? 'bg-purple-100 text-purple-700' :
                                   ($user->role === 'petugas' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium
                                {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <button wire:click="openEdit({{ $user->id }})"
                                        class="rounded bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700 hover:bg-gray-200">
                                    Edit
                                </button>
                                @if($user->id !== auth()->id())
                                <button wire:click="toggleActive({{ $user->id }})"
                                        class="rounded px-2 py-1 text-xs font-semibold {{ $user->is_active ? 'bg-red-100 text-red-700 hover:bg-red-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-12 text-center text-sm text-gray-500">Tidak ada pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
</div>