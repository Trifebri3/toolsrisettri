<nav class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @php
            $currentProject = (request()->routeIs('projects.show') || request()->routeIs('projects.paper.*')) 
                ? (request()->route('project') instanceof \App\Models\ResearchProject ? request()->route('project') : \App\Models\ResearchProject::find(request()->route('project')))
                : null;
            $mobileProject = $currentProject;
            $navItems = $navProjects ?? collect();
        @endphp

        <!-- ==================== MOBILE TOP BAR (when inside Project Workspace) ==================== -->
        @if ($mobileProject)
            <div class="md:hidden flex justify-between h-14 items-center">
                <div class="flex items-center space-x-2 truncate">
                    <a href="{{ route('projects.index') }}" class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 active:scale-90 transition shrink-0" title="Kembali ke Daftar Proyek">
                        <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </a>

                    <!-- Mobile Paper Quick Switcher -->
                    <div class="relative truncate" x-data="{ mobilePaperDropdown: false }" @click.outside="mobilePaperDropdown = false">
                        <button type="button" @click="mobilePaperDropdown = !mobilePaperDropdown" class="text-left flex items-center space-x-1.5 truncate max-w-[170px] active:scale-95 transition">
                            <div class="truncate">
                                <div class="flex items-center space-x-1">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shrink-0"></span>
                                    <h2 class="text-xs font-bold text-slate-900 truncate">{{ $mobileProject->title }}</h2>
                                    <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                                <div class="text-[10px] text-teal-700 font-semibold uppercase tracking-wider">{{ $mobileProject->field }} &middot; Ganti Paper</div>
                            </div>
                        </button>

                        <!-- Mobile Paper Dropdown -->
                        <div x-show="mobilePaperDropdown" x-cloak x-transition class="absolute left-0 mt-2 w-72 bg-white rounded-2xl shadow-2xl border border-slate-200 py-2 z-50">
                            <div class="px-3.5 py-2 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Pindah Paper / Proyek</span>
                                <a href="{{ route('projects.index') }}" class="text-[10px] font-bold text-teal-700">Semua Proyek</a>
                            </div>
                            <div class="max-h-60 overflow-y-auto divide-y divide-slate-100">
                                @foreach ($navItems as $np)
                                    <div class="p-2.5 hover:bg-slate-50 flex items-center justify-between gap-2 {{ $np->id === $mobileProject->id ? 'bg-teal-50/70 border-l-4 border-teal-600' : '' }}">
                                        <div class="min-w-0 flex-1">
                                            <a href="{{ route('projects.show', $np->id) }}" class="block text-xs font-bold text-slate-900 hover:text-teal-700 truncate">
                                                {{ $np->title }}
                                            </a>
                                            <div class="text-[10px] text-slate-400">{{ $np->field }}</div>
                                        </div>
                                        <div class="flex items-center space-x-1 shrink-0">
                                            <a href="{{ route('projects.show', $np->id) }}" class="px-2 py-0.5 rounded bg-slate-100 text-[10px] font-semibold text-slate-700">
                                                WS
                                            </a>
                                            <a href="{{ route('projects.show', ['project' => $np->id, 'tab' => 'paper']) }}" class="px-2 py-0.5 rounded bg-teal-100 text-[10px] font-bold text-teal-800">
                                                Paper
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center space-x-1.5 shrink-0">
                    <!-- Tutorial Button Mobile -->
                    <button type="button" @click="tutorialModal = true" class="w-8 h-8 rounded-xl bg-teal-50 text-teal-800 border border-teal-200 flex items-center justify-center font-bold text-xs active:scale-90 transition" title="Panduan Tutorial Riset">
                        <svg class="w-4 h-4 text-teal-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </button>
                    <a href="{{ route('projects.paper.print', $mobileProject->id) }}" target="_blank" class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 active:scale-90 transition" title="Preview Paper">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                    <button @click="globalActionSheet = true" class="w-8 h-8 rounded-xl bg-teal-50 text-teal-700 border border-teal-200 flex items-center justify-center font-bold text-xs active:scale-90 transition" title="Aksi Cepat">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    </button>
                    <!-- User Avatar -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="w-8 h-8 rounded-full bg-gradient-to-tr from-teal-500 to-emerald-500 text-white flex items-center justify-center font-bold text-xs active:scale-90 transition shadow-2xs">
                                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="px-4 py-2 border-b border-slate-100 text-xs text-slate-500">
                                Masuk sebagai <br>
                                <span class="font-semibold text-slate-800">{{ Auth::user()->name ?? '' }}</span>
                            </div>
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Pengaturan Akun') }}
                            </x-dropdown-link>
                            <button type="button" @click="tutorialModal = true" class="block w-full px-4 py-2 text-start text-xs leading-5 text-teal-800 hover:bg-teal-50 transition font-semibold">
                                {{ __('Panduan Tutorial Riset') }}
                            </button>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <span class="text-rose-600 font-medium">{{ __('Keluar (Log Out)') }}</span>
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>
        @endif

        <!-- ==================== STANDARD TOP BAR (Desktop & Tablet) ==================== -->
        <div class="{{ $mobileProject ? 'hidden md:flex' : 'flex' }} justify-between h-14 sm:h-16 items-center gap-2">
            <!-- Left: Brand Logo & Concise Nav Links -->
            <div class="flex items-center space-x-2 lg:space-x-4 min-w-0">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group shrink-0">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-gradient-to-tr from-sky-600 via-teal-600 to-emerald-500 flex items-center justify-center text-white shadow-sm shadow-emerald-500/20 group-hover:scale-105 transition-transform duration-200">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                    </div>
                    <div>
                        <span class="font-extrabold text-sm sm:text-base tracking-tight text-slate-900 group-hover:text-sky-700 transition">RESEARCH <span class="text-teal-600">OS</span></span>
                        <span class="hidden xl:block text-[9px] font-bold text-teal-700 tracking-wider -mt-1 uppercase">Sains, Teknologi & Pengabdian</span>
                    </div>
                </a>

                <!-- Desktop Concise Navigation Links (No more text collision!) -->
                <div class="hidden md:flex items-center space-x-0.5 xl:space-x-1 shrink-0">
                    <a href="{{ route('dashboard') }}" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('ideas.index') }}" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('ideas.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        Ide
                    </a>
                    <a href="{{ route('projects.index') }}" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('projects.*') ? 'bg-teal-50 text-teal-800 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        Workspace
                    </a>
                    <a href="{{ route('literature.index') }}" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('literature.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        Literatur
                    </a>
                    <a href="{{ route('outputs.index') }}" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold transition {{ request()->routeIs('outputs.*') ? 'bg-sky-50 text-sky-700 font-bold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        Luaran
                    </a>
                </div>
            </div>

            <!-- Right Actions: Paper Switcher, Panduan, Aksi Cepat, User Dropdown -->
            <div class="flex items-center space-x-1.5 sm:space-x-2 shrink-0">
                <!-- Paper Quick Switcher (Compact, Elegant, Non-colliding) -->
                <div class="relative hidden lg:block" x-data="{ openPaperSwitcher: false }" @click.outside="openPaperSwitcher = false">
                    <button type="button" @click="openPaperSwitcher = !openPaperSwitcher"
                            class="flex items-center space-x-1.5 px-2.5 py-1.5 rounded-xl border text-xs transition active:scale-95 {{ $currentProject ? 'bg-teal-50 border-teal-300 text-teal-900 font-bold shadow-2xs' : 'bg-slate-50 border-slate-200 text-slate-700 hover:bg-slate-100' }}"
                            title="{{ $currentProject ? $currentProject->title : 'Pilih Paper Riset' }}">
                        <span class="w-2 h-2 rounded-full {{ $currentProject ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }} shrink-0"></span>
                        <span class="text-xs truncate max-w-[100px] xl:max-w-[140px]">
                            {{ $currentProject ? Str::limit($currentProject->title, 16) : 'Pilih Paper' }}
                        </span>
                        <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="openPaperSwitcher"
                         x-cloak
                         x-transition
                         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-slate-200/90 py-2 z-50 overflow-hidden">
                        <div class="px-4 py-2.5 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Pilih Paper & Proyek Riset</span>
                            <a href="{{ route('projects.create') }}" class="text-[11px] font-bold text-teal-700 hover:underline">+ Proyek Baru</a>
                        </div>

                        <div class="max-h-72 overflow-y-auto divide-y divide-slate-100 py-1">
                            @forelse ($navItems as $proj)
                                <div class="p-3 hover:bg-teal-50/40 transition flex items-start justify-between gap-2 {{ $currentProject && $currentProject->id === $proj->id ? 'bg-teal-50/80 border-l-4 border-teal-600' : '' }}">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center space-x-1.5">
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold uppercase bg-slate-100 text-slate-700">{{ $proj->field }}</span>
                                            @if ($currentProject && $currentProject->id === $proj->id)
                                                <span class="px-1.5 py-0.5 rounded text-[9px] font-bold text-emerald-800 bg-emerald-100">Sedang Dibuka</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('projects.show', $proj->id) }}" class="block font-bold text-xs text-slate-900 hover:text-teal-700 truncate mt-1">
                                            {{ $proj->title }}
                                        </a>
                                        <div class="flex items-center space-x-3 text-[10px] text-slate-400 mt-1">
                                            <span>Claims: <strong class="text-slate-600">{{ $proj->claims_count }}</strong></span>
                                            <span>Bukti: <strong class="text-slate-600">{{ $proj->evidences_count }}</strong></span>
                                            <span>Target: <strong class="text-slate-600">{{ $proj->outputs_count }}</strong></span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-1 shrink-0 pt-0.5">
                                        <a href="{{ route('projects.show', $proj->id) }}" class="px-2 py-1 rounded-md text-[10px] font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                                            Workspace
                                        </a>
                                        <a href="{{ route('projects.show', ['project' => $proj->id, 'tab' => 'paper']) }}" class="px-2 py-1 rounded-md text-[10px] font-bold bg-teal-100 hover:bg-teal-200 text-teal-800 transition">
                                            Tulis Paper
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="py-6 text-center text-xs text-slate-400">
                                    Belum ada proyek riset aktif.
                                </div>
                            @endforelse
                        </div>

                        <div class="px-4 py-2 bg-slate-50 border-t border-slate-100 text-center">
                            <a href="{{ route('projects.index') }}" class="text-xs font-bold text-teal-700 hover:text-teal-800">
                                Lihat Semua Daftar Paper & Proyek ({{ $navItems->count() }}) →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Panduan Tutorial Button -->
                <button type="button" @click="tutorialModal = true" class="inline-flex items-center px-2.5 py-1.5 rounded-xl text-xs font-bold text-teal-800 bg-teal-50 hover:bg-teal-100 border border-teal-200/90 transition active:scale-95 space-x-1" title="Buka Panduan Tutorial Riset">
                    <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>Panduan</span>
                </button>

                <!-- Desktop Quick Capture Button (Compact, No more ugly collision!) -->
                <button @click="globalActionSheet = true" class="hidden sm:inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-sky-600 to-teal-600 hover:from-sky-700 hover:to-teal-700 shadow-sm transition transform active:scale-95 space-x-1 shrink-0">
                    <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Aksi Cepat</span>
                </button>

                <!-- Mobile Quick Action Button -->
                <button @click="globalActionSheet = true" class="sm:hidden w-8 h-8 rounded-lg bg-teal-50 text-teal-700 border border-teal-200 flex items-center justify-center font-bold text-xs active:scale-90 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                </button>

                <!-- User Dropdown (Responsive & Neat) -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center p-1 sm:px-2.5 sm:py-1.5 border border-slate-200 text-xs font-medium rounded-xl text-slate-700 bg-white hover:bg-slate-50 focus:outline-none transition active:scale-95">
                            <div class="w-7 h-7 sm:w-6 sm:h-6 rounded-full bg-gradient-to-tr from-teal-500 to-emerald-500 text-white flex items-center justify-center font-bold text-xs sm:me-1.5 shadow-2xs">
                                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="hidden sm:block max-w-[90px] xl:max-w-[120px] truncate text-left font-semibold text-slate-800">{{ Auth::user()->name ?? 'Peneliti' }}</div>
                            <svg class="hidden sm:block ms-1 h-3.5 w-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-slate-100 text-xs text-slate-500">
                            Masuk sebagai <br>
                            <span class="font-semibold text-slate-800">{{ Auth::user()->name ?? '' }}</span>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Pengaturan Akun') }}
                        </x-dropdown-link>
                        <button type="button" @click="tutorialModal = true" class="block w-full px-4 py-2 text-start text-xs leading-5 text-teal-800 hover:bg-teal-50 transition font-semibold">
                            {{ __('Panduan Tutorial Riset') }}
                        </button>
                        <button type="button" onclick="window.triggerPwaInstall()" class="block w-full px-4 py-2 text-start text-xs leading-5 text-teal-800 hover:bg-teal-50 transition font-semibold">
                            {{ __('Pasang Aplikasi (PWA)') }}
                        </button>
                        <x-dropdown-link :href="route('offline')">
                            {{ __('Catatan Riset Offline') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                <span class="text-rose-600 font-medium">{{ __('Keluar (Log Out)') }}</span>
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
