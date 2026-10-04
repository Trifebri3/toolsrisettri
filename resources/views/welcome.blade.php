<!DOCTYPE html>
<html lang="id" class="h-full bg-[#fbfcfd]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#0f766e">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Research OS">
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="icon" type="image/svg+xml" href="/icons/icon.svg">
    <link rel="alternate icon" type="image/png" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" href="/icons/apple-touch-icon.png">

    <title>Research OS — Research Repository & Paper Builder</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-[#fbfcfd] flex flex-col justify-between">
    <!-- Header -->
    <header class="border-b border-slate-200/80 bg-white/90 backdrop-blur sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-sky-600 via-teal-600 to-emerald-500 flex items-center justify-center text-white shadow-sm shadow-emerald-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                </div>
                <div class="leading-tight">
                    <span class="font-extrabold text-base tracking-tight text-slate-900">RESEARCH <span class="text-teal-600">OS</span></span>
                    <span class="block text-[10px] font-semibold text-slate-400 tracking-wider uppercase">Research Repository & Paper Builder</span>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 transition">
                            Buka Dashboard →
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 hover:text-slate-900 hover:bg-slate-100 transition">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-sky-600 via-teal-600 to-emerald-600 hover:opacity-95 shadow-sm transition">
                                Daftar Akun
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="flex-1 py-12 lg:py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Badge Tagline -->
            <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-teal-50 border border-teal-200/80 text-teal-800 text-xs font-semibold mb-6">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>From Ideas to Evidence, From Evidence to Knowledge</span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-tight">
                Operating System Penelitian Pribadi <br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-sky-600 via-teal-600 to-emerald-600 bg-clip-text text-transparent">
                    Berbasis Evidence, Anti-Halusinasi.
                </span>
            </h1>

            <p class="mt-5 text-base sm:text-lg text-slate-600 max-w-3xl mx-auto leading-relaxed">
                Ubah ide penelitian, catatan lapangan, literatur, dataset sensor, dan eksperimen menjadi aset terstruktur. Bangun draf paper ilmiah Scopus/Sinta, laporan pengabdian, dan repositori data yang dapat dilacak kembali ke sumber aslinya.
            </p>

            <!-- Flow Pipeline Bar -->
            <div class="mt-8 flex flex-wrap items-center justify-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs font-semibold text-slate-600">
                <span class="px-2.5 py-1 rounded-md bg-white border border-slate-200 shadow-2xs">Capture</span>
                <span class="text-teal-500 font-bold">→</span>
                <span class="px-2.5 py-1 rounded-md bg-white border border-slate-200 shadow-2xs">Organize</span>
                <span class="text-teal-500 font-bold">→</span>
                <span class="px-2.5 py-1 rounded-md bg-white border border-slate-200 shadow-2xs">Investigate</span>
                <span class="text-teal-500 font-bold">→</span>
                <span class="px-2.5 py-1 rounded-md bg-white border border-slate-200 shadow-2xs">Collect</span>
                <span class="text-teal-500 font-bold">→</span>
                <span class="px-2.5 py-1 rounded-md bg-white border border-slate-200 shadow-2xs">Analyze</span>
                <span class="text-teal-500 font-bold">→</span>
                <span class="px-2.5 py-1 rounded-md bg-sky-50 text-sky-700 border border-sky-200 font-bold">Write</span>
                <span class="text-teal-500 font-bold">→</span>
                <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold">Publish</span>
            </div>

            <!-- Primary Actions -->
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-sky-600 via-teal-600 to-emerald-600 hover:opacity-95 shadow-md shadow-teal-600/20 transition flex items-center justify-center space-x-2">
                        <span>Buka Dashboard Riset</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-6 py-3.5 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-sky-600 via-teal-600 to-emerald-600 hover:opacity-95 shadow-md shadow-teal-600/20 transition flex items-center justify-center space-x-2">
                        <span>Masuk ke Workspace Riset</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-5 py-3.5 rounded-xl font-semibold text-sm text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition">
                            Daftar Akun Peneliti
                        </a>
                    @endif
                @endauth
            </div>

            <!-- 4 Principle Pillars Cards -->
            <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-6 text-left">
                <!-- Pillar 1 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs hover:border-sky-300 transition">
                    <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center mb-4 font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-900">Anti-Halusinasi Ilmiah</h2>
                    <p class="mt-2 text-xs text-slate-600 leading-relaxed">
                        AI bertindak sebagai asisten sintesis, bukan pengarang fakta. Klaim ilmiah tanpa bukti akan ditandai <span class="text-rose-600 font-semibold">[Missing Evidence]</span> dan dilarang masuk ke naskah final.
                    </p>
                </div>

                <!-- Pillar 2 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs hover:border-teal-300 transition">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center mb-4 font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-900">Evidence → Finding → Claim</h2>
                    <p class="mt-2 text-xs text-slate-600 leading-relaxed">
                        Mekanisme rantai keterlacakan (traceability). Setiap grafik, tabel, dan argumen di Results & Discussion terhubung langsung ke dataset mentah atau log oscilloscope/sensor.
                    </p>
                </div>

                <!-- Pillar 3 -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-2xs hover:border-emerald-300 transition">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-4 font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/>
                        </svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-900">Multi-Output Engine</h2>
                    <p class="mt-2 text-xs text-slate-600 leading-relaxed">
                        Satu proyek riset menghasilkan naskah jurnal (Scopus/Sinta), prosiding konferensi, laporan pengabdian masyarakat (PKM), blueprint prototipe, dan open dataset.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200/80 bg-white py-6 text-center text-xs text-slate-400">
        <p>Research OS © 2026 • Dirancang untuk akademisi, dosen, dan peneliti independen.</p>
    </footer>
</body>
</html>
