<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('Welcome') }} - {{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @fluxAppearance
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-950 antialiased text-zinc-900 dark:text-zinc-100 flex flex-col">
        <header class="container mx-auto px-6 py-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="size-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                    <x-app-logo-icon class="size-6 fill-current" />
                </div>
                <span class="text-xl font-bold tracking-tight">{{ config('app.name', 'Perpus Digital') }}</span>
            </div>

            <nav class="flex items-center gap-4">
                @auth
                    <flux:button href="{{ route('dashboard') }}" variant="primary" wire:navigate>Dashboard</flux:button>
                @else
                    <flux:button href="{{ route('login') }}" variant="ghost">Log in</flux:button>
                    @if (Route::has('register'))
                        <flux:button href="{{ route('register') }}" variant="primary">Register</flux:button>
                    @endif
                @endauth
            </nav>
        </header>

        <main class="flex-1 flex flex-col items-center justify-center container mx-auto px-6 text-center">
            <div class="max-w-3xl space-y-8">
                <flux:badge variant="neutral" size="sm" class="uppercase tracking-widest font-bold px-4 py-1">Digital Library System</flux:badge>
                
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight leading-tight">
                    Akses Ribuan Buku dalam <span class="text-transparent bg-clip-text bg-linear-to-r from-indigo-600 to-purple-600">Satu Genggaman</span>
                </h1>
                
                <p class="text-xl text-zinc-500 dark:text-zinc-400 max-w-2xl mx-auto leading-relaxed">
                    Platform perpustakaan digital modern yang memudahkan Anda meminjam, membaca, dan mengelola koleksi buku favorit kapan saja dan di mana saja.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                    <flux:button href="{{ route('buku.index') }}" variant="primary" size="lg" class="px-8 py-6 text-lg rounded-2xl shadow-xl shadow-indigo-500/25">
                        Jelajahi Katalog
                    </flux:button>
                    <flux:button variant="outline" size="lg" class="px-8 py-6 text-lg rounded-2xl">
                        Pelajari Fitur
                    </flux:button>
                </div>

                <div class="pt-16 grid grid-cols-2 md:grid-cols-4 gap-8 opacity-50 grayscale hover:opacity-100 hover:grayscale-0 transition duration-500">
                    <div class="flex flex-col items-center">
                        <span class="text-3xl font-bold">10k+</span>
                        <span class="text-xs uppercase tracking-widest font-semibold">Judul Buku</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-3xl font-bold">5k+</span>
                        <span class="text-xs uppercase tracking-widest font-semibold">Pengguna Aktif</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-3xl font-bold">24/7</span>
                        <span class="text-xs uppercase tracking-widest font-semibold">Akses Online</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="text-3xl font-bold">100%</span>
                        <span class="text-xs uppercase tracking-widest font-semibold">Gratis</span>
                    </div>
                </div>
            </div>
        </main>

        <footer class="container mx-auto px-6 py-8 text-center text-zinc-400 text-sm">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </footer>

        @fluxScripts
    </body>
</html>
