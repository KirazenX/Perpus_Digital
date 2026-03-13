<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-950 antialiased">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-800 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('buku.index') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>
            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')" class="grid">

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
                    @else
                        <flux:sidebar.item icon="book-open" :href="route('buku.index')" :current="request()->routeIs('buku.*')" wire:navigate>
                            {{ __('Katalog Buku') }}
                        </flux:sidebar.item>
                    @endauth
                </flux:sidebar.group>
            </flux:sidebar.nav>
            <flux:spacer />
            @auth
                <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
            @else
                <div class="hidden lg:block p-4">
                    <flux:button href="{{ route('login') }}" variant="primary" class="w-full" wire:navigate>
                        {{ __('Login') }}
                    </flux:button>
                </div>
            @endauth
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            @auth
                <flux:dropdown position="top" align="end">
                    <flux:profile
                        :initials="auth()->user()->initials()"
                        icon-trailing="chevron-down"
                    />
                    <flux:menu>
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar
                                :name="auth()->user()->name"
                                :initials="auth()->user()->initials()"
                            />
                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>

                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                                {{ __('Settings') }}
                            </flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.submenu icon="swatch" :label="__('Appearance')">
                            <flux:menu.item x-on:click="$flux.appearance = 'light'" icon="sun">
                                {{ __('Light') }}
                            </flux:menu.item>
                            <flux:menu.item x-on:click="$flux.appearance = 'dark'" icon="moon">
                                {{ __('Dark') }}
                            </flux:menu.item>
                            <flux:menu.item x-on:click="$flux.appearance = 'system'" icon="computer-desktop">
                                {{ __('System') }}
                            </flux:menu.item>
                        </flux:menu.submenu>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full cursor-pointer">
                                {{ __('Log out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            @else
                <flux:button href="{{ route('login') }}" variant="primary" size="sm" wire:navigate>
                    {{ __('Login') }}
                </flux:button>
            @endauth
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
