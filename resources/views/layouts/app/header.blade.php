<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-950 antialiased">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />

            <x-app-logo href="{{ route('dashboard') }}" wire:navigate />

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navbar.item>
                @auth
                    @if(auth()->user()->isPeminjam())
                        <flux:navbar.item icon="book-open" :href="route('buku.index')" :current="request()->routeIs('buku.*')" wire:navigate>
                            {{ __('Katalog Buku') }}
                        </flux:navbar.item>
                        <flux:navbar.item icon="clipboard-document-list" :href="route('peminjaman.saya')" :current="request()->routeIs('peminjaman.saya')" wire:navigate>
                            {{ __('Peminjaman Saya') }}
                        </flux:navbar.item>
                    @endif

                    @if(auth()->user()->isStaff())
                        <flux:navbar.item icon="book-open" :href="route('buku.index')" :current="request()->routeIs('buku.index')" wire:navigate>
                            {{ __('Katalog Buku') }}
                        </flux:navbar.item>
                        <flux:navbar.item icon="pencil-square" :href="route('buku.create')" :current="request()->routeIs('buku.create')" wire:navigate>
                            {{ __('Tambah Buku') }}
                        </flux:navbar.item>
                        <flux:navbar.item icon="clipboard-document-check" :href="route('peminjaman.manage')" :current="request()->routeIs('peminjaman.manage')" wire:navigate>
                            {{ __('Kelola Peminjaman') }}
                        </flux:navbar.item>
                        <flux:navbar.item icon="chart-bar" :href="route('laporan.index')" :current="request()->routeIs('laporan.*')" wire:navigate>
                            {{ __('Laporan') }}
                        </flux:navbar.item>
                    @endif
                @endauth
            </flux:navbar>

            <flux:spacer />

            <x-desktop-user-menu />
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar collapsible="mobile" sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')">
                    <flux:sidebar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard')  }}
                    </flux:sidebar.item>
                    @auth
                        @if(auth()->user()->isPeminjam())
                            <flux:sidebar.item icon="book-open" :href="route('buku.index')" :current="request()->routeIs('buku.*')" wire:navigate>
                                {{ __('Katalog Buku') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="clipboard-document-list" :href="route('peminjaman.saya')" :current="request()->routeIs('peminjaman.saya')" wire:navigate>
                                {{ __('Peminjaman Saya') }}
                            </flux:sidebar.item>
                        @endif

                        @if(auth()->user()->isStaff())
                            <flux:sidebar.item icon="book-open" :href="route('buku.index')" :current="request()->routeIs('buku.index')" wire:navigate>
                                {{ __('Katalog Buku') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="pencil-square" :href="route('buku.create')" :current="request()->routeIs('buku.create')" wire:navigate>
                                {{ __('Tambah Buku') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="clipboard-document-check" :href="route('peminjaman.manage')" :current="request()->routeIs('peminjaman.manage')" wire:navigate>
                                {{ __('Kelola Peminjaman') }}
                            </flux:sidebar.item>
                            <flux:sidebar.item icon="chart-bar" :href="route('laporan.index')" :current="request()->routeIs('laporan.*')" wire:navigate>
                                {{ __('Laporan') }}
                            </flux:sidebar.item>
                        @endif

                        @if(auth()->user()->isAdministrator())
                            <flux:sidebar.item icon="users" :href="route('admin.pengguna')" :current="request()->routeIs('admin.*')" wire:navigate>
                                {{ __('Manajemen Pengguna') }}
                            </flux:sidebar.item>
                        @endif
                    @endauth
                </flux:sidebar.group>
            </flux:sidebar.nav>
        </flux:sidebar>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
