{{-- resources/views/components/navigation.blade.php --}}
{{-- atau tambahkan ke layout utama Anda (biasanya resources/views/layouts/app.blade.php atau navigation.blade.php dari Livewire starter kit) --}}

{{-- Tambahkan link-link navigasi berikut ke navbar yang sudah ada dari starter kit Livewire Anda --}}

{{-- Nav links untuk semua user yang login --}}
<nav class="flex items-center gap-1">
    <a href="{{ route('buku.index') }}"
       class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('buku.index') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
        Katalog Buku
    </a>

    @auth
        @if(auth()->user()->isPeminjam())
            <a href="{{ route('peminjaman.saya') }}"
               class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('peminjaman.saya') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                Peminjaman Saya
            </a>
        @endif

        @if(auth()->user()->isStaff())
            <a href="{{ route('peminjaman.manage') }}"
               class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('peminjaman.manage') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                Kelola Peminjaman
            </a>
            <a href="{{ route('laporan.index') }}"
               class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('laporan.index') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                Laporan
            </a>
        @endif

        @if(auth()->user()->isAdministrator())
            <a href="{{ route('admin.pengguna') }}"
               class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.pengguna') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                Manajemen Pengguna
            </a>
        @endif
    @endauth
</nav>