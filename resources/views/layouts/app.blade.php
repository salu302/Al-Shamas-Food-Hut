<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#0D0D0D] text-white min-h-screen">
        <div class="min-h-screen bg-[#0D0D0D]">
            @include('layouts.navigation')

            @if(session('error') || session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    @if(session('error'))
                        <div class="rounded-lg bg-red-600 text-white px-4 py-3">{{ session('error') }}</div>
                    @endif
                    @if(session('success'))
                        <div class="rounded-lg bg-green-600 text-white px-4 py-3">{{ session('success') }}</div>
                    @endif
                </div>
            @endif

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-[#181818] shadow-sm border-b border-slate-800">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="pb-16">
                {{ $slot }}
            </main>

            <footer class="border-t border-slate-800 bg-[#0D0D0D]">
                <div class="max-w-7xl mx-auto flex flex-col items-center justify-between gap-3 px-4 py-6 text-sm text-slate-400 sm:flex-row sm:px-6 lg:px-8">
                    <p>© {{ date('Y') }} Al-Shamas Pizza Hut</p>
                    <a href="{{ route('complaints.create') }}" class="font-medium text-[#FFB703] hover:text-[#ffd166]">Complaints & Suggestions</a>
                </div>
            </footer>
        </div>
    </body>
</html>
