<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#0f766e">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="Research OS">
        <link rel="manifest" href="/manifest.webmanifest">
        <link rel="icon" type="image/svg+xml" href="/icons/icon.svg">
        <link rel="alternate icon" type="image/png" href="/icons/icon-192x192.png">
        <link rel="apple-touch-icon" href="/icons/apple-touch-icon.png">

        <title>{{ config('app.name', 'Research OS') }} — Masuk</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-800 antialiased bg-slate-50 selection:bg-teal-500 selection:text-white min-h-screen flex flex-col justify-between relative overflow-x-hidden">
        <!-- Ambient Background Mesh Glow -->
        <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden">
            <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[650px] h-[400px] bg-gradient-to-tr from-teal-200/40 via-sky-100/40 to-emerald-100/30 blur-3xl rounded-full"></div>
            <div class="absolute bottom-0 right-0 w-[400px] h-[300px] bg-gradient-to-bl from-teal-100/30 via-slate-100/40 to-transparent blur-2xl rounded-full"></div>
        </div>

        <div class="flex-1 flex flex-col sm:justify-center items-center px-4 py-8 sm:py-12">
            <div class="mb-6 animate-fade-in">
                <a href="/" class="transition hover:opacity-90 inline-block">
                    <x-application-logo />
                </a>
            </div>

            <div class="w-full sm:max-w-md bg-white/95 backdrop-blur-md px-6 py-7 sm:p-8 rounded-3xl shadow-xl shadow-slate-900/5 border border-slate-200/80 animate-scale-in">
                {{ $slot }}
            </div>
        </div>

        <!-- Footer -->
        <footer class="py-5 text-center text-xs text-slate-400">
            <p class="font-medium tracking-wide">Research OS &copy; {{ date('Y') }} &middot; From Ideas to Evidence, From Evidence to Knowledge</p>
        </footer>
    </body>
</html>
