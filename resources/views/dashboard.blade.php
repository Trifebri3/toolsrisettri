<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5 sm:space-y-6">

        <!-- Mobile Greeting Card (Native App Home Banner) -->
        <div class="sm:hidden bg-gradient-to-r from-sky-600 via-teal-600 to-emerald-600 rounded-2xl p-4 text-white shadow-md shadow-teal-700/15 space-y-2.5 animate-fade-in">
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-bold tracking-wider uppercase text-teal-100">Personal Research OS</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] bg-white/20 font-bold backdrop-blur-xs flex items-center space-x-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                    <span>Aktif</span>
                </span>
            </div>
            <div>
                <h2 class="text-base font-extrabold tracking-tight">Halo, {{ Auth::user()->name ?? 'Peneliti' }}</h2>
                <p class="text-xs text-teal-50/90 leading-relaxed mt-0.5">
                    Ubah ide riset, data lapangan, & bukti empiris menjadi naskah paper terverifikasi.
                </p>
            </div>
            <div class="pt-1 flex items-center gap-2">
                <button @click="globalActionSheet = true" class="px-3.5 py-1.5 rounded-xl bg-white text-teal-800 text-xs font-bold active:scale-95 shadow-sm transition flex items-center space-x-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Aksi Cepat</span>
                </button>
                <a href="{{ route('projects.index') }}" class="px-3.5 py-1.5 rounded-xl bg-white/20 text-white hover:bg-white/30 text-xs font-semibold active:scale-95 transition">
                    Buka Riset →
                </a>
            </div>
        </div>

        <!-- Top Header & Quick Capture Trigger Bar (Desktop & Tablet) -->
        <div class="hidden sm:flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2 border-b border-slate-200/80">
            <div>
                <div class="flex items-center space-x-2 text-xs font-semibold text-teal-600">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    <span class="tracking-wide uppercase">Research Operating System</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">Workspace Peneliti</h1>
                <p class="text-xs text-slate-500">Pantau progres riset, bukti lapangan (evidence), dan tindakan prioritas berikutnya.</p>
            </div>

            <!-- Quick Stats Pills -->
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar touch-pan-x pb-1 sm:pb-0">
                <div class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-xs shadow-2xs flex items-center space-x-2 shrink-0">
                    <span class="text-slate-500">Ide:</span>
                    <span class="font-bold text-sky-700">{{ $totalIdeas }}</span>
                </div>
                <div class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-xs shadow-2xs flex items-center space-x-2 shrink-0">
                    <span class="text-slate-500">Riset:</span>
                    <span class="font-bold text-teal-700">{{ $totalProjects }}</span>
                </div>
                <div class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-xs shadow-2xs flex items-center space-x-2 shrink-0">
                    <span class="text-slate-500">Grounded Claims:</span>
                    <span class="font-bold text-emerald-700">{{ $groundedClaims }}</span>
                </div>
                <div class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-xs shadow-2xs flex items-center space-x-2 shrink-0">
                    <span class="text-slate-500">Publikasi:</span>
                    <span class="font-bold text-slate-800">{{ $publishedOutputs }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Stats Pills Row on Mobile -->
        <div class="sm:hidden flex items-center gap-2 overflow-x-auto no-scrollbar touch-pan-x pb-1">
            <div class="px-3 py-2 rounded-xl bg-white border border-slate-200/90 text-xs shadow-2xs flex items-center space-x-2 shrink-0">
                <span class="text-slate-500">Ide:</span>
                <span class="font-bold text-sky-700">{{ $totalIdeas }}</span>
            </div>
            <div class="px-3 py-2 rounded-xl bg-white border border-slate-200/90 text-xs shadow-2xs flex items-center space-x-2 shrink-0">
                <span class="text-slate-500">Riset:</span>
                <span class="font-bold text-teal-700">{{ $totalProjects }}</span>
            </div>
            <div class="px-3 py-2 rounded-xl bg-white border border-slate-200/90 text-xs shadow-2xs flex items-center space-x-2 shrink-0">
                <span class="text-slate-500">Claims:</span>
                <span class="font-bold text-emerald-700">{{ $groundedClaims }}</span>
            </div>
            <div class="px-3 py-2 rounded-xl bg-white border border-slate-200/90 text-xs shadow-2xs flex items-center space-x-2 shrink-0">
                <span class="text-slate-500">Luaran:</span>
                <span class="font-bold text-slate-800">{{ $publishedOutputs }}</span>
            </div>
        </div>        <!-- Tutorial & Onboarding Hero Card -->
        <div class="bg-gradient-to-r from-teal-900 via-teal-800 to-slate-900 rounded-2xl p-4 sm:p-5 text-white shadow-md border border-teal-700/50 flex flex-col md:flex-row md:items-center justify-between gap-4 animate-fade-in">
            <div class="flex items-start sm:items-center space-x-3.5">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-teal-500/20 border border-teal-400/30 flex items-center justify-center shrink-0 text-teal-300 shadow-inner">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-teal-400/20 text-teal-300 border border-teal-400/30">
                            Panduan Cepat & Interaktif
                        </span>
                        <span class="text-xs text-teal-200 hidden sm:inline">&middot; 5 Menit Paham Alur Riset</span>
                    </div>
                    <h2 class="text-sm sm:text-base font-extrabold text-white mt-1">
                        Bingung cara menyusun paper dan alur riset di Research OS?
                    </h2>
                    <p class="text-xs text-slate-300 mt-0.5 leading-relaxed">
                        Pelajari siklus 5 tahap: <strong>1. Ide Vault</strong> &rarr; <strong>2. Pondasi & RQ</strong> &rarr; <strong>3. Data & Bukti</strong> &rarr; <strong>4. Klaim Terverifikasi</strong> &rarr; <strong>5. Paper Builder</strong>.
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <button type="button" @click="tutorialModal = true" class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-teal-400 hover:bg-teal-300 text-slate-950 font-black text-xs shadow-md transition transform active:scale-95 flex items-center justify-center space-x-1.5">
                    <svg class="w-4 h-4 text-slate-950" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Buka Panduan Tutorial</span>
                </button>
            </div>
        </div>

        <!-- 2-Column Grid: Left (Active Research & Next Action) | Right (Research Readiness & Quick Capture) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left Main Column (7 cols) -->
            <div class="lg:col-span-7 space-y-6">

                <!-- 1. Next Action Banner (Highest Priority) -->
                @if (!empty($nextActions))
                    <div class="bg-gradient-to-r from-sky-50 via-teal-50 to-emerald-50 border border-teal-200/90 rounded-2xl p-5 shadow-2xs">
                        <div class="flex items-center justify-between mb-2.5">
                            <div class="flex items-center space-x-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-teal-600 text-white shadow-2xs">
                                    Next Action
                                </span>
                                <span class="text-xs font-semibold text-slate-600">Pekerjaan Paling Penting Berikutnya:</span>
                            </div>
                            <span class="text-[11px] text-teal-700 font-medium">Auto-Recommended</span>
                        </div>

                        <div class="space-y-2 mt-3">
                            @foreach ($nextActions as $action)
                                <div class="bg-white/90 backdrop-blur rounded-xl p-3 border border-teal-100 flex items-center justify-between gap-3 shadow-2xs hover:bg-white transition">
                                    <div class="flex items-start space-x-3">
                                        <div class="mt-0.5">
                                            @if ($action['badge_color'] === 'blue')
                                                <div class="w-5 h-5 rounded-md bg-sky-100 text-sky-700 flex items-center justify-center">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                </div>
                                            @elseif ($action['badge_color'] === 'amber')
                                                <div class="w-5 h-5 rounded-md bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-xs">!</div>
                                            @else
                                                <div class="w-5 h-5 rounded-md bg-emerald-100 text-emerald-700 flex items-center justify-center">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-slate-900 leading-snug">{{ $action['action'] }}</p>
                                            <p class="text-[11px] text-slate-500 mt-0.5">Project: <span class="font-medium text-slate-700">{{ Str::limit($action['project'], 40) }}</span> • <span class="text-teal-700 font-semibold">{{ $action['phase'] }}</span></p>
                                        </div>
                                    </div>
                                    <a href="{{ route('projects.show', $action['project_id']) }}" class="shrink-0 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 transition">
                                        Kerjakan →
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- 2. Active Research Projects & Papers -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Paper & Proyek Riset Aktif</h2>
                            <p class="text-xs text-slate-400">Pilih paper yang ingin dikerjakan, buka workspace lengkap atau langsung tulis draft paper.</p>
                        </div>
                        <a href="{{ route('projects.index') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-800 transition">
                            Lihat Semua ({{ $totalProjects }}) →
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse ($projects as $project)
                            @php
                                $statusBadge = match($project->status) {
                                    'drafting' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'label' => 'Drafting Foundation'],
                                    'data_collection' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-800', 'label' => 'Data Collection'],
                                    'analysis' => ['bg' => 'bg-sky-50', 'text' => 'text-sky-800', 'label' => 'Analysis & Claims'],
                                    'paper_writing' => ['bg' => 'bg-teal-50', 'text' => 'text-teal-800', 'label' => 'Paper Builder'],
                                    'under_review' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-800', 'label' => 'Under Review'],
                                    'published' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-800', 'label' => 'Published'],
                                    default => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'label' => ucfirst($project->status)],
                                };
                                $score = $project->readiness_breakdown['overall'] ?? $project->readiness_score;
                            @endphp
                            <div class="p-4 rounded-xl border border-slate-200/70 hover:border-teal-300 transition bg-white shadow-2xs group">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                    <div class="space-y-1">
                                        <div class="flex items-center space-x-2">
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }}">
                                                {{ $statusBadge['label'] }}
                                            </span>
                                            <span class="text-[11px] text-slate-400">•</span>
                                            <span class="text-xs font-medium text-slate-500">{{ $project->field }}</span>
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-900 group-hover:text-teal-700 transition">
                                            <a href="{{ route('projects.show', $project->id) }}">
                                                {{ $project->title }}
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="flex items-center space-x-2 shrink-0 self-end sm:self-center">
                                        <span class="text-xs font-extrabold text-teal-700 hidden sm:inline">{{ $score }}%</span>
                                        <a href="{{ route('projects.show', $project->id) }}" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                                            Workspace
                                        </a>
                                        <a href="{{ route('projects.show', ['project' => $project->id, 'tab' => 'paper']) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-teal-600 hover:bg-teal-700 transition flex items-center space-x-1 shadow-2xs">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Tulis Paper</span>
                                        </a>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mt-3">
                                    <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="bg-gradient-to-r from-sky-500 to-teal-500 h-1.5 rounded-full transition-all duration-500" style="width: {{ $score }}%"></div>
                                    </div>
                                </div>

                                <!-- Quick Project Stats -->
                                <div class="mt-3 pt-2.5 border-t border-slate-100 flex flex-wrap items-center gap-x-4 gap-y-1 text-[11px] text-slate-500">
                                    <span>Datasets: <strong class="text-slate-700">{{ $project->datasets->count() }}</strong></span>
                                    <span>Bukti (Evidence): <strong class="text-slate-700">{{ $project->evidences->count() }}</strong></span>
                                    <span>Scientific Claims: <strong class="text-slate-700">{{ $project->claims->count() }}</strong></span>
                                    <span>Target Luaran: <strong class="text-slate-700">{{ $project->outputs->count() }}</strong></span>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-slate-400 text-xs">
                                Belum ada penelitian aktif. Tangkap ide terlebih dahulu atau buat project baru.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- 3. Recent Evidence Stream -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Recent Evidence Stream</h2>
                            <p class="text-[11px] text-slate-400">Data lapangan, sensor telemetry, kalibrasi, dan dokumen terbaru.</p>
                        </div>
                        <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">
                            Ground-Truth Verified
                        </span>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($recentEvidences as $ev)
                            <div class="py-2.5 flex items-start justify-between gap-3 text-xs">
                                <div class="flex items-start space-x-2.5">
                                    <div class="w-6 h-6 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center shrink-0 mt-0.5 font-bold text-[10px]">
                                        {{ substr(strtoupper($ev->evidence_type), 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $ev->title }}</p>
                                        <p class="text-[11px] text-slate-500 line-clamp-1">{{ $ev->description }}</p>
                                        <p class="text-[10px] text-slate-400 mt-0.5">Project: {{ $ev->project->title ?? '-' }} • {{ $ev->collected_at ? $ev->collected_at->format('d M Y') : 'Terbaru' }}</p>
                                    </div>
                                </div>
                                <span class="shrink-0 text-[10px] font-bold px-2 py-0.5 rounded-full {{ $ev->quality_status === 'ground_truth' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ strtoupper($ev->quality_status) }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-3 text-center">Belum ada evidence yang diunggah.</p>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Right Column (5 cols): Research Readiness Index & Quick Capture -->
            <div class="lg:col-span-5 space-y-6">

                <!-- 1. Quick Capture Widget (< 1 Minute) -->
                <div id="quick-capture" class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <h2 class="text-sm font-bold text-slate-900">Quick Capture Idea (< 1m)</h2>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400">AI Auto-Classify</span>
                    </div>

                    <form method="POST" action="{{ route('ideas.store') }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Judul / Inti Ide Riset</label>
                            <input type="text" name="title" required placeholder="Contoh: Pemisahan Mikroplastik dengan Ultrasonik..."
                                class="w-full text-xs rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 placeholder-slate-400 transition">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Catatan Singkat / Latar Belakang (Opsional)</label>
                            <textarea name="description" rows="2" placeholder="Tuliskan ide spontan Anda dalam beberapa kalimat..."
                                class="w-full text-xs rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 placeholder-slate-400 transition"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-[10px] font-semibold text-slate-500 mb-1">Bidang Kajian</label>
                                <input type="text" name="field" placeholder="Otomatis oleh AI"
                                    class="w-full text-xs rounded-lg border-slate-200 focus:border-teal-500 focus:ring-teal-500">
                            </div>
                            <div>
                                <label class="block text-[10px] font-semibold text-slate-500 mb-1">Prioritas</label>
                                <select name="priority" class="w-full text-xs rounded-lg border-slate-200 focus:border-teal-500 focus:ring-teal-500">
                                    <option value="high">Tinggi (High)</option>
                                    <option value="medium" selected>Sedang (Medium)</option>
                                    <option value="low">Rendah (Low)</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-2.5 rounded-xl font-bold text-xs text-white bg-gradient-to-r from-sky-600 via-teal-600 to-emerald-600 hover:opacity-95 shadow-sm transition">
                            + Simpan & Klasifikasi Otomatis
                        </button>
                    </form>
                </div>

                <!-- 2. Research Readiness Index Breakdown -->
                @if ($primaryProject && $readiness)
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-4">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <div>
                                <h2 class="text-sm font-bold text-slate-900">Research Readiness Index</h2>
                                <p class="text-[11px] text-slate-400">Tingkat kesiapan proyek utama: <span class="font-semibold text-slate-700">{{ Str::limit($primaryProject->title, 28) }}</span></p>
                            </div>
                            <div class="text-right">
                                <span class="text-xl font-black text-teal-700">{{ $readiness['overall'] }}%</span>
                                <span class="block text-[9px] text-slate-400 uppercase font-bold">Kesiapan</span>
                            </div>
                        </div>

                        <!-- Overall Big Bar -->
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-gradient-to-r from-sky-500 via-teal-500 to-emerald-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $readiness['overall'] }}%"></div>
                        </div>

                        <!-- 5 Readiness Pillars -->
                        <div class="space-y-3 pt-1">
                            <!-- Foundation -->
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="font-semibold text-slate-700">1. Research Foundation</span>
                                    <span class="font-bold text-sky-700">{{ $readiness['foundation']['score'] }}/{{ $readiness['foundation']['max'] }} pts</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="bg-sky-500 h-1.5 rounded-full" style="width: {{ $readiness['foundation']['pct'] }}%"></div>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-0.5">Problem, Gap, Novelty, Research Questions</p>
                            </div>

                            <!-- Methodology -->
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="font-semibold text-slate-700">2. Methodology & Rigor</span>
                                    <span class="font-bold text-sky-700">{{ $readiness['methodology']['score'] }}/{{ $readiness['methodology']['max'] }} pts</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="bg-sky-500 h-1.5 rounded-full" style="width: {{ $readiness['methodology']['pct'] }}%"></div>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-0.5">Desain, Sampel, Variabel, Instrumen, Metrik</p>
                            </div>

                            <!-- Data & Evidence -->
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="font-semibold text-slate-700">3. Data & Evidence Collection</span>
                                    <span class="font-bold text-teal-700">{{ $readiness['data']['score'] }}/{{ $readiness['data']['max'] }} pts</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="bg-teal-500 h-1.5 rounded-full" style="width: {{ $readiness['data']['pct'] }}%"></div>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-0.5">Dataset terverifikasi & log pengukuran lapangan</p>
                            </div>

                            <!-- Analysis & Claims -->
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="font-semibold text-slate-700">4. Analysis & Grounded Claims</span>
                                    <span class="font-bold text-teal-700">{{ $readiness['analysis']['score'] }}/{{ $readiness['analysis']['max'] }} pts</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="bg-teal-500 h-1.5 rounded-full" style="width: {{ $readiness['analysis']['pct'] }}%"></div>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-0.5">Finding terhubung dengan bukti (Anti-Hallucination check)</p>
                            </div>

                            <!-- Writing / Paper Builder -->
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="font-semibold text-slate-700">5. Paper Writing Readiness</span>
                                    <span class="font-bold text-emerald-700">{{ $readiness['writing']['score'] }}/{{ $readiness['writing']['max'] }} pts</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $readiness['writing']['pct'] }}%"></div>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-0.5">Draf section akademik yang telah disusun</p>
                            </div>
                        </div>

                        <div class="pt-2">
                            <a href="{{ route('projects.show', $primaryProject->id) }}" class="block w-full text-center py-2 rounded-xl text-xs font-bold text-teal-800 bg-teal-50 border border-teal-200/80 hover:bg-teal-100 transition">
                                Buka Detail Kesiapan di Workspace →
                            </a>
                        </div>
                    </div>
                @endif

                <!-- 3. Recent Ideas List -->
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                        <h2 class="text-sm font-bold text-slate-900">Recent Captured Ideas</h2>
                        <a href="{{ route('ideas.index') }}" class="text-xs font-semibold text-sky-600 hover:text-sky-800">
                            Semua ({{ $totalIdeas }}) →
                        </a>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($recentIdeas as $idea)
                            <div class="py-2.5 flex items-start justify-between gap-2 text-xs">
                                <div>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $idea->status === 'promoted' ? 'bg-teal-50 text-teal-800' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $idea->status === 'promoted' ? 'Sudah Jadi Project' : 'Captured Idea' }}
                                    </span>
                                    <h4 class="font-bold text-slate-900 mt-1 line-clamp-1">{{ $idea->title }}</h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5">{{ $idea->field }}</p>
                                </div>
                                @if ($idea->status === 'captured')
                                    <form method="POST" action="{{ route('ideas.promote', $idea->id) }}">
                                        @csrf
                                        <button type="submit" class="shrink-0 px-2.5 py-1 rounded-lg text-[11px] font-bold text-teal-700 bg-teal-50 border border-teal-200 hover:bg-teal-100 transition">
                                            Promote →
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-2 text-center">Belum ada ide yang disimpan.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
