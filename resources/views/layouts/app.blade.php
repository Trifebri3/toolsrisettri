<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#fbfcfd]">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
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

        <title>{{ config('app.name', 'Research OS') }} — From Ideas to Evidence, From Evidence to Knowledge</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="h-full antialiased text-slate-800 bg-[#fbfcfd] flex flex-col" x-data="{ globalActionSheet: false, actionSheetTab: 'idea', tutorialModal: false }">
        <!-- Top App Header -->
        @include('layouts.navigation')

        <!-- Flash Notifications -->
        @if (session('success') || session('warning') || session('info') || session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-3 w-full">
                @if (session('success'))
                    <div class="rounded-xl bg-emerald-50 border border-emerald-200/80 p-3 sm:p-3.5 flex items-center space-x-3 text-xs sm:text-sm text-emerald-800 shadow-sm animate-fade-in">
                        <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0 text-emerald-700">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="flex-1 font-medium">{{ session('success') }}</div>
                    </div>
                @endif
                @if (session('warning'))
                    <div class="rounded-xl bg-amber-50 border border-amber-200/80 p-3 sm:p-3.5 flex items-center space-x-3 text-xs sm:text-sm text-amber-900 shadow-sm animate-fade-in">
                        <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-lg bg-amber-100 flex items-center justify-center shrink-0 text-amber-700">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div class="flex-1 font-medium">{{ session('warning') }}</div>
                    </div>
                @endif
                @if (session('info'))
                    <div class="rounded-xl bg-sky-50 border border-sky-200/80 p-3 sm:p-3.5 flex items-center space-x-3 text-xs sm:text-sm text-sky-900 shadow-sm animate-fade-in">
                        <div class="w-5 h-5 sm:w-6 sm:h-6 rounded-lg bg-sky-100 flex items-center justify-center shrink-0 text-sky-700">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1 font-medium">{{ session('info') }}</div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Main Content Area with Bottom Padding for Mobile Bar -->
        <main class="flex-1 py-4 sm:py-6 pb-24 md:pb-8">
            {{ $slot }}
        </main>

        <!-- Minimalist Desktop Footer -->
        <footer class="hidden md:block border-t border-slate-200/70 bg-white py-5 text-center text-xs text-slate-400">
            <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                <div class="flex items-center space-x-2">
                    <span class="font-bold text-slate-700">RESEARCH OS</span>
                    <span class="text-slate-300">•</span>
                    <span>Personal Research Repository & Paper Builder</span>
                </div>
                <div class="text-slate-400 text-[11px]">
                    Capture → Organize → Investigate → Collect → Analyze → Write → Publish
                </div>
            </div>
        </footer>

        <!-- ==================== MOBILE APP BOTTOM NAVIGATION BAR (HP NATIVE FEEL) ==================== -->
        <nav class="md:hidden fixed bottom-0 inset-x-0 bg-white/95 backdrop-blur-lg border-t border-slate-200/90 z-40 shadow-lg safe-bottom">
            <div class="grid grid-cols-5 h-16 items-center px-1">
                <!-- 1. Home / Dashboard -->
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center py-1 transition active:scale-90 {{ request()->routeIs('dashboard') ? 'text-teal-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'stroke-[2.5]' : 'stroke-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="text-[10px] mt-0.5 tracking-tight">Beranda</span>
                    @if (request()->routeIs('dashboard'))
                        <span class="w-1 h-1 rounded-full bg-teal-600 mt-0.5"></span>
                    @endif
                </a>

                <!-- 2. Ide (Idea Repo) -->
                <a href="{{ route('ideas.index') }}" class="flex flex-col items-center justify-center py-1 transition active:scale-90 {{ request()->routeIs('ideas.*') ? 'text-teal-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('ideas.*') ? 'stroke-[2.5]' : 'stroke-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                    <span class="text-[10px] mt-0.5 tracking-tight">Ide</span>
                    @if (request()->routeIs('ideas.*'))
                        <span class="w-1 h-1 rounded-full bg-teal-600 mt-0.5"></span>
                    @endif
                </a>

                <!-- 3. Center Elevated + Quick Action Button -->
                <div class="flex items-center justify-center">
                    <button @click="globalActionSheet = true" class="w-12 h-12 -mt-5 rounded-full bg-gradient-to-tr from-sky-600 via-teal-600 to-emerald-500 text-white flex items-center justify-center shadow-lg shadow-teal-600/30 border-2 border-white transition active:scale-90 transform">
                        <svg class="w-6 h-6 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </div>

                <!-- 4. Research Workspace -->
                <a href="{{ route('projects.index') }}" class="flex flex-col items-center justify-center py-1 transition active:scale-90 {{ request()->routeIs('projects.*') ? 'text-teal-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('projects.*') ? 'stroke-[2.5]' : 'stroke-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                    <span class="text-[10px] mt-0.5 tracking-tight">Riset</span>
                    @if (request()->routeIs('projects.*'))
                        <span class="w-1 h-1 rounded-full bg-teal-600 mt-0.5"></span>
                    @endif
                </a>

                <!-- 5. Pustaka & Luaran -->
                <a href="{{ route('literature.index') }}" class="flex flex-col items-center justify-center py-1 transition active:scale-90 {{ request()->routeIs('literature.*') || request()->routeIs('outputs.*') ? 'text-teal-600 font-bold' : 'text-slate-500 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('literature.*') || request()->routeIs('outputs.*') ? 'stroke-[2.5]' : 'stroke-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="text-[10px] mt-0.5 tracking-tight">Pustaka</span>
                    @if (request()->routeIs('literature.*') || request()->routeIs('outputs.*'))
                        <span class="w-1 h-1 rounded-full bg-teal-600 mt-0.5"></span>
                    @endif
                </a>
            </div>
        </nav>

        <!-- ==================== MOBILE MULTI-ACTION BOTTOM SHEET (NATIVE APP FEEL) ==================== -->
        @php
            $userGlobalProjects = auth()->check() ? \App\Models\ResearchProject::where('user_id', auth()->id())->select('id', 'title')->latest()->get() : collect();
        @endphp

        <div x-show="globalActionSheet"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4"
             x-cloak>
            
            <div @click.away="globalActionSheet = false"
                 x-transition:enter="transition ease-out duration-250"
                 x-transition:enter-start="translate-y-full sm:scale-95"
                 x-transition:enter-end="translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-y-0 sm:scale-100"
                 x-transition:leave-end="translate-y-full sm:scale-95"
                 class="bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-2xl p-5 sm:p-6 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto space-y-4">
                
                <!-- Drawer Pull Handle (Mobile) -->
                <div class="sm:hidden w-12 h-1.5 bg-slate-200 rounded-full mx-auto -mt-1 mb-2"></div>

                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">
                            <span>Aksi Cepat Peneliti</span>
                        </h3>
                        <p class="text-[11px] text-slate-400">Pilih tindakan cepat untuk langsung dicatat ke sistem</p>
                    </div>
                    <button @click="globalActionSheet = false" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-900 active:scale-90 transition" title="Tutup">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- 3-Segment Tab Switcher -->
                <div class="grid grid-cols-3 gap-1 bg-slate-100/90 p-1 rounded-xl">
                    <button type="button" @click="actionSheetTab = 'idea'"
                            :class="actionSheetTab === 'idea' ? 'bg-white text-teal-700 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                            class="py-2 text-xs rounded-lg transition active:scale-95 flex items-center justify-center space-x-1">
                        <span>Ide Kilat</span>
                    </button>
                    <button type="button" @click="actionSheetTab = 'evidence'"
                            :class="actionSheetTab === 'evidence' ? 'bg-white text-teal-700 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                            class="py-2 text-xs rounded-lg transition active:scale-95 flex items-center justify-center space-x-1">
                        <span>Bukti Data</span>
                    </button>
                    <button type="button" @click="actionSheetTab = 'project'"
                            :class="actionSheetTab === 'project' ? 'bg-white text-teal-700 font-bold shadow-2xs' : 'text-slate-600 hover:text-slate-900'"
                            class="py-2 text-xs rounded-lg transition active:scale-95 flex items-center justify-center space-x-1">
                        <span>Riset Baru</span>
                    </button>
                </div>

                <!-- ================= TAB 1: QUICK IDEA CAPTURE ================= -->
                <div x-show="actionSheetTab === 'idea'" class="space-y-3">
                    <form method="POST" action="{{ route('ideas.store') }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Judul / Inti Ide Riset *</label>
                            <input type="text" name="title" required placeholder="Contoh: Drone AI Deteksi Bleaching Karang..."
                                   class="w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 transition py-2.5 px-3 text-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Catatan Singkat (Opsional)</label>
                            <textarea name="description" rows="2" placeholder="Masalah yang ingin dipecahkan atau ide metodologi..."
                                      class="w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 transition py-2 px-3 text-sm"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-2.5">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Bidang Kajian</label>
                                <input type="text" name="field" placeholder="Otomatis oleh AI" class="w-full rounded-lg border-slate-200 py-1.5 px-2.5 text-xs">
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Prioritas</label>
                                <select name="priority" class="w-full rounded-lg border-slate-200 py-1.5 px-2.5 text-xs">
                                    <option value="high">Tinggi (High)</option>
                                    <option value="medium" selected>Sedang (Medium)</option>
                                    <option value="low">Rendah (Backlog)</option>
                                </select>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full py-3 rounded-xl font-bold text-xs text-white bg-gradient-to-r from-sky-600 via-teal-600 to-emerald-600 hover:opacity-95 active:scale-95 shadow-md shadow-teal-600/20 transition flex items-center justify-center space-x-1.5">
                                <span>+ Simpan Ide & Klasifikasi AI</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- ================= TAB 2: QUICK EVIDENCE LOGGER ================= -->
                <div x-show="actionSheetTab === 'evidence'" class="space-y-3" x-data="{ selectedProject: '{{ $userGlobalProjects->first()->id ?? '' }}' }">
                    @if ($userGlobalProjects->count() > 0)
                        <form :action="'/projects/' + selectedProject + '/evidences'" method="POST" class="space-y-3">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1">Pilih Proyek Penelitian *</label>
                                <select x-model="selectedProject" class="w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 text-xs font-semibold">
                                    @foreach ($userGlobalProjects as $gp)
                                        <option value="{{ $gp->id }}">{{ Str::limit($gp->title, 45) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1">Judul Bukti Empiris *</label>
                                <input type="text" name="title" required placeholder="Contoh: Kalibrasi Sensor Node B R² = 0.982"
                                       class="w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 text-xs">
                            </div>

                            <div class="grid grid-cols-2 gap-2.5">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Tipe Bukti</label>
                                    <select name="evidence_type" class="w-full rounded-lg border-slate-200 py-1.5 px-2 text-xs">
                                        <option value="sensor_reading">Pembacaan Sensor</option>
                                        <option value="data_point">Data Point / Statistik</option>
                                        <option value="experiment_metric">Metrik Eksperimen</option>
                                        <option value="test_log">Log Pengujian</option>
                                        <option value="photo">Foto Dokumentasi</option>
                                        <option value="quote">Kutipan Wawancara</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Kualitas Bukti</label>
                                    <select name="quality_status" class="w-full rounded-lg border-slate-200 py-1.5 px-2 text-xs">
                                        <option value="ground_truth">Baku Mutu (Ground Truth)</option>
                                        <option value="verified" selected>Terverifikasi</option>
                                        <option value="unverified">Belum Diverifikasi</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1">Deskripsi Faktual *</label>
                                <textarea name="description" rows="2" required placeholder="Uraikan nilai kuantitatif atau temuan lapangan..."
                                          class="w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 text-xs"></textarea>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full py-3 rounded-xl font-bold text-xs text-white bg-gradient-to-r from-teal-600 to-emerald-600 hover:opacity-95 active:scale-95 shadow-md shadow-teal-600/20 transition flex items-center justify-center space-x-1.5">
                                    <span>+ Catat Bukti Lapangan Sekarang</span>
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="py-6 text-center text-slate-400 text-xs space-y-2">
                            <p>Belum ada proyek penelitian aktif.</p>
                            <button type="button" @click="actionSheetTab = 'project'" class="px-3 py-1.5 bg-teal-50 text-teal-700 font-bold rounded-lg border border-teal-200 text-xs">
                                Buat Proyek Pertama →
                            </button>
                        </div>
                    @endif
                </div>

                <!-- ================= TAB 3: QUICK PROJECT CREATOR ================= -->
                <div x-show="actionSheetTab === 'project'" class="space-y-3">
                    <form method="POST" action="{{ route('projects.store') }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Judul Penelitian *</label>
                            <input type="text" name="title" required placeholder="Contoh: Sistem Akustik Deteksi Kebocoran Pipa..."
                                   class="w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 py-2.5 px-3 text-sm">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5" x-data="{ quickField: 'Sains & Teknologi' }">
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Bidang / Topik Utama *</label>
                                <input type="text" name="field" x-model="quickField" required placeholder="Contoh: Sains & Teknologi, PKM"
                                       class="w-full rounded-lg border-slate-200 py-1.5 px-2.5 text-xs">
                                <div class="flex items-center gap-1 mt-1">
                                    <button type="button" @click="quickField = 'Sains & Teknologi'" class="text-[9px] px-1.5 py-0.5 rounded bg-sky-50 text-sky-800 border border-sky-200 font-bold">
                                        Saintek
                                    </button>
                                    <button type="button" @click="quickField = 'Pengabdian Masyarakat (PKM)'" class="text-[9px] px-1.5 py-0.5 rounded bg-emerald-50 text-emerald-800 border border-emerald-200 font-bold">
                                        PKM
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Target Deadline</label>
                                <input type="date" name="target_deadline" class="w-full rounded-lg border-slate-200 py-1.5 px-2 text-xs">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Ringkasan Tujuan (Opsional)</label>
                            <textarea name="summary" rows="2" placeholder="Garis besar masalah dan apa yang ingin dicapai..."
                                      class="w-full rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 py-2 px-3 text-xs"></textarea>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full py-3 rounded-xl font-bold text-xs text-white bg-slate-900 hover:bg-slate-800 active:scale-95 shadow-md transition flex items-center justify-center space-x-1.5">
                                <span>+ Inisialisasi Workspace Riset</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Quick Navigation Shortcuts -->
                <div class="pt-2 border-t border-slate-100 flex items-center justify-around text-[11px] text-slate-500">
                    <button type="button" @click="globalActionSheet = false; tutorialModal = true" class="hover:text-teal-700 font-bold py-1 px-2 rounded-md hover:bg-teal-50 text-teal-800 transition">
                        Panduan Riset
                    </button>
                    <span class="text-slate-300">•</span>
                    <a href="{{ route('literature.index') }}" class="hover:text-teal-700 font-medium py-1 px-2 rounded-md hover:bg-slate-50">
                        Literatur
                    </a>
                    <span class="text-slate-300">•</span>
                    <a href="{{ route('outputs.index') }}" class="hover:text-teal-700 font-medium py-1 px-2 rounded-md hover:bg-slate-50">
                        Luaran
                    </a>
                    <span class="text-slate-300">•</span>
                    <a href="{{ route('ideas.index') }}" class="hover:text-teal-700 font-medium py-1 px-2 rounded-md hover:bg-slate-50">
                        Ide
                    </a>
                </div>

            </div>
        </div>

        <!-- Interactive Research OS Tutorial Modal -->
        <x-tutorial-modal />

    </body>
</html>
