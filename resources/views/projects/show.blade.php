<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 pb-20 sm:pb-8" x-data="{
        activeTab: '{{ $activeTab }}',
        tabsOrder: ['overview', 'foundation', 'methodology', 'data', 'analysis', 'paper', 'outputs', 'ai'],
        tabLabels: {
            overview: 'Ringkasan',
            foundation: 'Pondasi & RQ',
            methodology: 'Metodologi',
            data: 'Data & Bukti',
            analysis: 'Analisis & Klaim',
            paper: 'Paper Builder',
            outputs: 'Multi-Output',
            ai: 'AI Assistant'
        },
        get currentStepIndex() {
            return this.tabsOrder.indexOf(this.activeTab);
        },
        get prevTab() {
            let idx = this.currentStepIndex;
            return idx > 0 ? this.tabsOrder[idx - 1] : null;
        },
        get nextTab() {
            let idx = this.currentStepIndex;
            return idx < this.tabsOrder.length - 1 ? this.tabsOrder[idx + 1] : null;
        },
        goToTab(tab) {
            if (!tab) return;
            this.activeTab = tab;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        summaryExpanded: false,
        addDocumentModal: false,
        addDatasetModal: false,
        addEvidenceModal: false,
        addFindingModal: false,
        addClaimModal: false,
        linkEvidenceModal: false,
        activeClaimId: null,
        addOutputModal: false,
        addQuestionModal: false,
        editSectionModal: false,
        activeSection: null
    }">

        <!-- Top Breadcrumb & Status Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-1 border-b border-slate-200/70 text-xs">
            <div class="flex items-center space-x-2 text-slate-500 flex-wrap">
                <a href="{{ route('projects.index') }}" class="hover:text-slate-900 transition flex items-center space-x-1 font-semibold">
                    <span>← Daftar Paper</span>
                </a>
                <span>/</span>
                <span class="font-bold text-teal-700 uppercase tracking-wider">{{ $project->field }}</span>
                <span>/</span>
                <span class="text-slate-800 font-bold truncate max-w-[180px] sm:max-w-xs">{{ $project->title }}</span>
            </div>

            <!-- Right Controls: Panduan Tutorial, Hapus, & Status Dropdown -->
            <div class="flex items-center space-x-2 self-start sm:self-center flex-wrap">
                <!-- Panduan Paper Button -->
                <button type="button" @click="tutorialModal = true" class="px-2.5 py-1 rounded-lg text-xs font-bold text-teal-800 bg-teal-50 border border-teal-200 hover:bg-teal-100 transition flex items-center space-x-1.5 active:scale-95 shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>Panduan Alur Riset</span>
                </button>

                <!-- Delete Project Button -->
                <form method="POST" action="{{ route('projects.destroy', $project->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh proyek riset ini: {{ addslashes($project->title) }} beserta seluruh dokumen dan naskahnya?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 border border-rose-200 transition active:scale-95 flex items-center space-x-1" title="Hapus Proyek Ini">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span>Hapus</span>
                    </button>
                </form>

                <div class="flex items-center space-x-1.5">
                    <span class="text-slate-400">Status:</span>
                    <form method="POST" action="{{ route('projects.update', $project->id) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_tab" value="{{ $activeTab }}">
                        <select name="status" onchange="this.form.submit()" class="text-xs font-bold rounded-lg border-slate-200 bg-white py-1 pl-2.5 pr-8 focus:border-teal-500 focus:ring-teal-500 text-teal-800">
                            <option value="drafting" {{ $project->status === 'drafting' ? 'selected' : '' }}>Drafting Foundation</option>
                            <option value="data_collection" {{ $project->status === 'data_collection' ? 'selected' : '' }}>Data Collection</option>
                            <option value="analysis" {{ $project->status === 'analysis' ? 'selected' : '' }}>Analysis & Claims</option>
                            <option value="paper_writing" {{ $project->status === 'paper_writing' ? 'selected' : '' }}>Paper Builder</option>
                            <option value="under_review" {{ $project->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                            <option value="published" {{ $project->status === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <!-- Project Hero Card (White Seamless with Blue/Green Accents) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-4 sm:p-6 shadow-2xs space-y-3 sm:space-y-4">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="space-y-1.5 flex-1">
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-teal-50 text-teal-800 border border-teal-200/80">
                            Research Workspace
                        </span>
                        <span class="text-xs text-slate-400">•</span>
                        <span class="text-xs text-slate-500">ID: RS-{{ str_pad($project->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight leading-tight">
                        {{ $project->title }}
                    </h1>
                    
                    <!-- Collapsible summary on mobile for maximum ergonomics -->
                    <div>
                        <p class="text-xs text-slate-600 leading-relaxed max-w-4xl" :class="summaryExpanded ? '' : 'line-clamp-2 sm:line-clamp-none'">
                            {{ $project->summary ?? 'Tambahkan ringkasan proyek untuk memulai orientasi tim dan asisten AI.' }}
                        </p>
                        @if ($project->summary && strlen($project->summary) > 120)
                            <button @click="summaryExpanded = !summaryExpanded" class="sm:hidden text-[11px] font-bold text-teal-700 mt-1 hover:underline">
                                <span x-text="summaryExpanded ? '▲ Tutup Ringkasan' : '▼ Baca Selengkapnya'"></span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Readiness Gauge Card -->
                <div class="flex items-center lg:flex-col lg:items-end justify-between shrink-0 bg-slate-50/80 lg:bg-transparent p-3 lg:p-0 rounded-xl border lg:border-0 border-slate-100">
                    <div class="text-left lg:text-right">
                        <div class="flex items-baseline space-x-1.5">
                            <span class="text-2xl sm:text-3xl font-black text-teal-700 tracking-tight">{{ $readiness['overall'] }}%</span>
                            <span class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase">Readiness</span>
                        </div>
                        <div class="w-28 sm:w-36 bg-slate-200/70 rounded-full h-2 mt-1 overflow-hidden">
                            <div class="bg-gradient-to-r from-sky-500 to-teal-500 h-2 rounded-full transition-all duration-500" style="width: {{ $readiness['overall'] }}%"></div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Kesiapan Naskah & Data</p>
                    </div>

                    <div class="mt-2 flex items-center space-x-2">
                        <a href="{{ route('projects.paper.print', $project->id) }}" target="_blank" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition shadow-2xs flex items-center space-x-1 active:scale-95">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>Preview Paper</span>
                        </a>
                        <a href="{{ route('projects.paper.export-markdown', $project->id) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 transition shadow-2xs flex items-center space-x-1 active:scale-95">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Export .MD</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Anti-Hallucination Guardrail Banner -->
            @if ($unbackedClaimsCount > 0)
                <div class="bg-rose-50/90 border border-rose-200/90 rounded-xl p-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-rose-900 animate-fade-in">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-6 h-6 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-xs shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <span class="font-bold">Anti-Hallucination Guardrail Aktif:</span>
                            <span class="text-rose-800">Ditemukan <strong>{{ $unbackedClaimsCount }} Scientific Claim</strong> tanpa bukti (Missing Evidence). Klaim ini tidak akan masuk ke draf final hingga bukti dihubungkan.</span>
                        </div>
                    </div>
                    <button @click="activeTab = 'analysis'" class="self-start sm:self-center px-3 py-1.5 bg-white border border-rose-300 rounded-lg text-xs font-bold text-rose-800 hover:bg-rose-50 transition shrink-0 active:scale-95">
                        Audit Klaim Sekarang &rarr;
                    </button>
                </div>
            @else
                <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-xl p-2.5 flex items-center space-x-2 text-xs text-emerald-900">
                    <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs shrink-0">
                        <svg class="w-3 h-3 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <span><strong>Integritas Ilmiah Terjaga:</strong> Seluruh {{ $groundedClaimsCount }} klaim hasil penelitian didukung oleh bukti empiris (Grounded Evidence).</span>
                </div>
            @endif
        </div>

        <!-- Horizontal Mobile-Swipeable Segmented Tab Bar (Native Pill UX) -->
        <div class="border-b border-slate-200/80 bg-white/95 backdrop-blur-md rounded-2xl p-1.5 shadow-2xs flex items-center gap-1.5 overflow-x-auto no-scrollbar touch-pan-x sticky top-14 sm:top-16 z-30">
            <button @click="activeTab = 'overview'"
                    :class="activeTab === 'overview' ? 'bg-teal-600 text-white font-bold shadow-md shadow-teal-600/25 border-teal-600' : 'bg-slate-50/80 text-slate-700 hover:text-slate-900 hover:bg-slate-100 border-slate-200/70'"
                    class="shrink-0 whitespace-nowrap px-3.5 py-2 rounded-xl text-xs transition border flex items-center space-x-1.5 active:scale-95">
                <span>Ringkasan & Kesiapan</span>
            </button>

            <button @click="activeTab = 'foundation'"
                    :class="activeTab === 'foundation' ? 'bg-teal-600 text-white font-bold shadow-md shadow-teal-600/25 border-teal-600' : 'bg-slate-50/80 text-slate-700 hover:text-slate-900 hover:bg-slate-100 border-slate-200/70'"
                    class="shrink-0 whitespace-nowrap px-3.5 py-2 rounded-xl text-xs transition border flex items-center space-x-1.5 active:scale-95">
                <span>Pondasi & RQ</span>
                <span :class="activeTab === 'foundation' ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700'" class="px-1.5 py-0.5 rounded-full text-[10px] font-bold">{{ $project->questions->count() }}</span>
            </button>

            <button @click="activeTab = 'methodology'"
                    :class="activeTab === 'methodology' ? 'bg-teal-600 text-white font-bold shadow-md shadow-teal-600/25 border-teal-600' : 'bg-slate-50/80 text-slate-700 hover:text-slate-900 hover:bg-slate-100 border-slate-200/70'"
                    class="shrink-0 whitespace-nowrap px-3.5 py-2 rounded-xl text-xs transition border flex items-center space-x-1.5 active:scale-95">
                <span>Metodologi</span>
            </button>

            <button @click="activeTab = 'data'"
                    :class="activeTab === 'data' ? 'bg-teal-600 text-white font-bold shadow-md shadow-teal-600/25 border-teal-600' : 'bg-slate-50/80 text-slate-700 hover:text-slate-900 hover:bg-slate-100 border-slate-200/70'"
                    class="shrink-0 whitespace-nowrap px-3.5 py-2 rounded-xl text-xs transition border flex items-center space-x-1.5 active:scale-95">
                <span>Data & Dokumen</span>
                <span :class="activeTab === 'data' ? 'bg-white/20 text-white' : 'bg-teal-100 text-teal-800'" class="px-1.5 py-0.5 rounded-full text-[10px] font-bold">{{ $project->evidences->count() + $project->documents->count() }}</span>
            </button>

            <button @click="activeTab = 'analysis'"
                    :class="activeTab === 'analysis' ? 'bg-teal-600 text-white font-bold shadow-md shadow-teal-600/25 border-teal-600' : 'bg-slate-50/80 text-slate-700 hover:text-slate-900 hover:bg-slate-100 border-slate-200/70'"
                    class="shrink-0 whitespace-nowrap px-3.5 py-2 rounded-xl text-xs transition border flex items-center space-x-1.5 active:scale-95">
                <span>Analisis & Klaim</span>
                <span :class="activeTab === 'analysis' ? 'bg-white/20 text-white' : '{{ $unbackedClaimsCount > 0 ? 'bg-rose-100 text-rose-800 font-extrabold' : 'bg-emerald-100 text-emerald-800 font-bold' }}'" class="px-1.5 py-0.5 rounded-full text-[10px]">{{ $project->claims->count() }}</span>
            </button>

            <button @click="activeTab = 'paper'"
                    :class="activeTab === 'paper' ? 'bg-teal-600 text-white font-bold shadow-md shadow-teal-600/25 border-teal-600' : 'bg-slate-50/80 text-slate-700 hover:text-slate-900 hover:bg-slate-100 border-slate-200/70'"
                    class="shrink-0 whitespace-nowrap px-3.5 py-2 rounded-xl text-xs transition border flex items-center space-x-1.5 active:scale-95">
                <span>Paper Builder</span>
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
            </button>

            <button @click="activeTab = 'outputs'"
                    :class="activeTab === 'outputs' ? 'bg-teal-600 text-white font-bold shadow-md shadow-teal-600/25 border-teal-600' : 'bg-slate-50/80 text-slate-700 hover:text-slate-900 hover:bg-slate-100 border-slate-200/70'"
                    class="shrink-0 whitespace-nowrap px-3.5 py-2 rounded-xl text-xs transition border flex items-center space-x-1.5 active:scale-95">
                <span>Multi-Output</span>
                <span :class="activeTab === 'outputs' ? 'bg-white/20 text-white' : 'bg-sky-100 text-sky-800'" class="px-1.5 py-0.5 rounded-full text-[10px] font-bold">{{ $project->outputs->count() }}</span>
            </button>

            <button @click="activeTab = 'ai'"
                    :class="activeTab === 'ai' ? 'bg-purple-600 text-white font-bold shadow-md shadow-purple-600/25 border-purple-600' : 'bg-slate-50/80 text-slate-700 hover:text-slate-900 hover:bg-slate-100 border-slate-200/70'"
                    class="shrink-0 whitespace-nowrap px-3.5 py-2 rounded-xl text-xs transition border flex items-center space-x-1.5 active:scale-95">
                <span>AI Assistant</span>
            </button>
        </div>

        <!-- ==================== TAB 1: OVERVIEW & READINESS ==================== -->
        <div x-show="activeTab === 'overview'" class="space-y-6 animate-fade-in" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Left (7 cols): Dynamic Research Checklist -->
                <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Research Dynamic Checklist</h2>
                            <p class="text-[11px] text-slate-400">Checklist dinamis yang beradaptasi dengan tahapan penelitian Anda.</p>
                        </div>
                        <button @click="$refs.newTaskForm.classList.toggle('hidden')" class="px-2.5 py-1 rounded-lg text-xs font-semibold text-teal-700 bg-teal-50 border border-teal-200 hover:bg-teal-100 transition">
                            + Tambah Tugas
                        </button>
                    </div>

                    <!-- Add task form -->
                    <div x-ref="newTaskForm" class="hidden p-3 bg-slate-50 rounded-xl border border-slate-200/70">
                        <form method="POST" action="{{ route('projects.tasks.store', $project->id) }}" class="space-y-2">
                            @csrf
                            <input type="text" name="task_name" required placeholder="Nama tugas / checklist baru..." class="w-full text-xs rounded-lg border-slate-200">
                            <div class="flex items-center justify-between">
                                <select name="phase" class="text-xs rounded-lg border-slate-200 py-1">
                                    <option value="foundation">Phase: Foundation</option>
                                    <option value="methodology">Phase: Methodology</option>
                                    <option value="data">Phase: Data Collection</option>
                                    <option value="analysis">Phase: Analysis</option>
                                    <option value="writing">Phase: Paper Writing</option>
                                    <option value="publication">Phase: Publication</option>
                                </select>
                                <button type="submit" class="px-3 py-1 bg-slate-900 text-white rounded-lg text-xs font-bold">Simpan</button>
                            </div>
                        </form>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @forelse ($project->tasks as $task)
                            <div class="py-2.5 flex items-start justify-between gap-3 text-xs">
                                <div class="flex items-start space-x-3">
                                    <form method="POST" action="{{ route('projects.tasks.toggle', ['project' => $project->id, 'task' => $task->id]) }}">
                                        @csrf
                                        <button type="submit" class="w-5 h-5 rounded border {{ $task->is_completed ? 'bg-emerald-600 border-emerald-600 text-white' : 'border-slate-300 bg-white hover:border-teal-500' }} flex items-center justify-center transition">
                                            @if ($task->is_completed)
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                            @endif
                                        </button>
                                    </form>
                                    <div>
                                        <p class="font-medium {{ $task->is_completed ? 'line-through text-slate-400' : 'text-slate-800' }}">
                                            {{ $task->task_name }}
                                        </p>
                                        <span class="text-[10px] uppercase font-bold text-slate-400">{{ $task->phase }}</span>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ $task->is_completed ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                    {{ $task->is_completed ? 'Selesai' : 'Pending' }}
                                </span>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-3 text-center">Belum ada checklist.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Right (5 cols): Readiness Radar Breakdown & Project Metadata -->
                <div class="lg:col-span-5 space-y-5">
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-4">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <h2 class="text-sm font-bold text-slate-900">Readiness Score Breakdown</h2>
                            <span class="text-xs font-black text-teal-700">{{ $readiness['overall'] }}% Total</span>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-slate-600 font-semibold">1. Foundation (Problem, Gap, Novelty, RQ)</span>
                                    <span class="font-bold text-sky-700">{{ $readiness['foundation']['score'] }}/{{ $readiness['foundation']['max'] }}</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="bg-sky-500 h-1.5 rounded-full" style="width: {{ $readiness['foundation']['pct'] }}%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-slate-600 font-semibold">2. Methodology & Rigor</span>
                                    <span class="font-bold text-sky-700">{{ $readiness['methodology']['score'] }}/{{ $readiness['methodology']['max'] }}</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="bg-sky-500 h-1.5 rounded-full" style="width: {{ $readiness['methodology']['pct'] }}%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-slate-600 font-semibold">3. Data & Evidence Collection</span>
                                    <span class="font-bold text-teal-700">{{ $readiness['data']['score'] }}/{{ $readiness['data']['max'] }}</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="bg-teal-500 h-1.5 rounded-full" style="width: {{ $readiness['data']['pct'] }}%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-slate-600 font-semibold">4. Analysis & Grounded Claims</span>
                                    <span class="font-bold text-teal-700">{{ $readiness['analysis']['score'] }}/{{ $readiness['analysis']['max'] }}</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="bg-teal-500 h-1.5 rounded-full" style="width: {{ $readiness['analysis']['pct'] }}%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-slate-600 font-semibold">5. Paper Writing Progress</span>
                                    <span class="font-bold text-emerald-700">{{ $readiness['writing']['score'] }}/{{ $readiness['writing']['max'] }}</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5">
                                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $readiness['writing']['pct'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Metadata Edit -->
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-3">
                        <h2 class="text-sm font-bold text-slate-900 pb-2 border-b border-slate-100">Informasi Proyek</h2>
                        <form method="POST" action="{{ route('projects.update', $project->id) }}" class="space-y-3">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="_tab" value="overview">

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Judul Riset</label>
                                <input type="text" name="title" value="{{ $project->title }}" class="w-full text-xs rounded-lg border-slate-200">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Bidang Kajian</label>
                                <input type="text" name="field" value="{{ $project->field }}" class="w-full text-xs rounded-lg border-slate-200">
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Target Deadline</label>
                                <input type="date" name="target_deadline" value="{{ $project->target_deadline ? $project->target_deadline->format('Y-m-d') : '' }}" class="w-full text-xs rounded-lg border-slate-200">
                            </div>

                            <button type="submit" class="w-full py-2 bg-slate-900 text-white font-bold text-xs rounded-lg hover:bg-slate-800 transition">
                                Simpan Perubahan Proyek
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <!-- ==================== TAB 2: RESEARCH FOUNDATION & QUESTIONS ==================== -->
        <div x-show="activeTab === 'foundation'" class="space-y-6 animate-fade-in" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Left (7 cols): Foundation Core Fields -->
                <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-2xs space-y-5">
                    <div class="border-b border-slate-100 pb-3">
                        <h2 class="text-base font-bold text-slate-900">Research Foundation</h2>
                        <p class="text-xs text-slate-400">Fondasi ilmiah yang menjadi dasar perumusan novelty dan kontribusi.</p>
                    </div>

                    <form method="POST" action="{{ route('projects.update', $project->id) }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_tab" value="foundation">

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-xs font-bold text-slate-800">1. Problem Statement (Latar Belakang Masalah)</label>
                                <span class="text-[10px] text-teal-600 font-semibold">Dasar Urgensi</span>
                            </div>
                            <textarea name="problem_statement" rows="3" class="w-full text-xs rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500">{{ $project->problem_statement }}</textarea>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-xs font-bold text-slate-800">2. Research Gap (Celah Penelitian)</label>
                                <span class="text-[10px] text-teal-600 font-semibold">Keterbatasan Studi Terdahulu</span>
                            </div>
                            <textarea name="research_gap" rows="3" class="w-full text-xs rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500">{{ $project->research_gap }}</textarea>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-xs font-bold text-slate-800">3. Novelty (Kebaruan Pendekatan)</label>
                                <span class="text-[10px] text-teal-600 font-semibold">Poin Inovasi Utama</span>
                            </div>
                            <textarea name="novelty" rows="3" class="w-full text-xs rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500">{{ $project->novelty }}</textarea>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="text-xs font-bold text-slate-800">4. Scientific Contribution (Kontribusi Ilmiah)</label>
                                <span class="text-[10px] text-teal-600 font-semibold">Teoretis & Praktis</span>
                            </div>
                            <textarea name="contribution" rows="3" class="w-full text-xs rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500">{{ $project->contribution }}</textarea>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-xs text-white bg-slate-900 hover:bg-slate-800 transition shadow-sm">
                                Simpan Pondasi Penelitian
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right (5 cols): Research Questions (RQs) & Objectives -->
                <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 p-6 shadow-2xs space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h2 class="text-sm font-bold text-slate-900">Research Questions & Objectives</h2>
                            <p class="text-[11px] text-slate-400">Pertanyaan ilmiah yang akan dijawab oleh bukti (evidence).</p>
                        </div>
                        <button @click="addQuestionModal = true" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-teal-800 bg-teal-50 border border-teal-200 hover:bg-teal-100 transition">
                            + Tambah RQ
                        </button>
                    </div>

                    <!-- RQ List -->
                    <div class="space-y-3">
                        @forelse ($project->questions as $idx => $rq)
                            <div class="p-3.5 rounded-xl border border-slate-200/70 bg-white space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-extrabold text-teal-700 bg-teal-50 px-2 py-0.5 rounded text-[10px]">
                                        RQ #{{ $idx + 1 }}
                                    </span>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ $rq->status === 'answered' ? 'bg-emerald-100 text-emerald-800' : ($rq->status === 'investigating' ? 'bg-sky-100 text-sky-800' : 'bg-slate-100 text-slate-700') }}">
                                            {{ strtoupper($rq->status) }}
                                        </span>
                                        <form method="POST" action="{{ route('projects.questions.destroy', ['project' => $project->id, 'question' => $rq->id]) }}" onsubmit="return confirm('Hapus RQ ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-rose-600 transition">×</button>
                                        </form>
                                    </div>
                                </div>
                                <p class="font-bold text-slate-900">{{ $rq->question }}</p>
                                @if ($rq->objective)
                                    <p class="text-slate-500 text-[11px] bg-slate-50 p-2 rounded-lg border border-slate-100">
                                        <strong>Objektif:</strong> {{ $rq->objective }}
                                    </p>
                                @endif
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-4 text-center">Belum ada Research Question yang dirumuskan.</p>
                        @endforelse
                    </div>

                    <!-- Literatures linked to this foundation -->
                    <div class="pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-bold text-slate-900">Referensi Terkait ({{ $project->literatures->count() }})</span>
                            <a href="{{ route('literature.index', ['project_id' => $project->id]) }}" class="text-[11px] font-semibold text-sky-600 hover:text-sky-800">
                                Matriks Literatur →
                            </a>
                        </div>
                        <div class="space-y-1.5">
                            @foreach ($project->literatures->take(3) as $lit)
                                <div class="text-[11px] text-slate-600 bg-slate-50 p-2 rounded-lg border border-slate-100">
                                    <strong class="text-slate-800">{{ $lit->authors }} ({{ $lit->year }})</strong> — {{ Str::limit($lit->title, 45) }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ==================== TAB 3: METHODOLOGY ==================== -->
        <div x-show="activeTab === 'methodology'" class="space-y-6 animate-fade-in" x-cloak>
            <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-2xs space-y-5">
                <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Methodology & Experimental Rigor</h2>
                        <p class="text-xs text-slate-400">Rancangan metodologi, populasi eksperimen, variabel terukur, instrumen baku, dan metrik evaluasi.</p>
                    </div>
                    <span class="text-xs font-bold text-teal-700 bg-teal-50 px-2.5 py-1 rounded-md border border-teal-200">
                        Rigor Checklist: {{ $readiness['methodology']['score'] }}/{{ $readiness['methodology']['max'] }} pts
                    </span>
                </div>

                <form method="POST" action="{{ route('projects.update', $project->id) }}" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_tab" value="methodology">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">1. Research Design</label>
                            <input type="text" name="methodology_design" value="{{ $project->methodology_design }}" placeholder="Contoh: Experimental Field Deployment & Design Science Research (DSR)" class="w-full text-xs rounded-xl border-slate-200">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">2. Sample / Populasi Eksperimen</label>
                            <input type="text" name="sample_population" value="{{ $project->sample_population }}" placeholder="Contoh: 12 simpul node pada sawah 2.5ha di Jatiluwih, Bali" class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">3. Variabel Penelitian (Bebas, Terikat, Kontrol)</label>
                        <textarea name="variables" rows="3" placeholder="Variabel Bebas: Kedalaman sensor, interval duty-cycle. Variabel Terikat: Konsumsi arus (mA), Packet Delivery Ratio (PDR %)..." class="w-full text-xs rounded-xl border-slate-200">{{ $project->variables }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">4. Instrumen & Peralatan Kalibrasi</label>
                        <textarea name="instruments" rows="3" placeholder="Peralatan hardware, sensor baku mutu, oscilloscope, lab oven..." class="w-full text-xs rounded-xl border-slate-200">{{ $project->instruments }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">5. Prosedur Eksperimen / Pengujian Lapangan</label>
                        <textarea name="procedure" rows="3" placeholder="Langkah 1: Kalibrasi lab oven 105°C; Langkah 2: Deploy di teras sawah; Langkah 3: Logging telemetri 30 hari..." class="w-full text-xs rounded-xl border-slate-200">{{ $project->procedure }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">6. Metrik Evaluasi Statistik (Evaluation Metrics)</label>
                        <input type="text" name="evaluation_metrics" value="{{ $project->evaluation_metrics }}" placeholder="Contoh: RMSE, MAPE, R², Packet Delivery Ratio (PDR %), Usia Baterai" class="w-full text-xs rounded-xl border-slate-200">
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-xs text-white bg-slate-900 hover:bg-slate-800 transition shadow-sm">
                            Simpan Desain Metodologi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ==================== TAB 4: DATA & EVIDENCE REPOSITORY ==================== -->
        <div x-show="activeTab === 'data'" class="space-y-6 animate-fade-in" x-cloak>
            
            <!-- Section Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-2xs">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Dokumen, Evidence & Data Repository</h2>
                    <p class="text-xs text-slate-500">Berkas dokumen riset, dataset sensor, foto lapangan, dan bukti empiris pondasi klaim penelitian.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <button @click="addDocumentModal = true" class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-white bg-gradient-to-r from-teal-600 to-emerald-600 hover:opacity-95 transition shadow-sm flex items-center space-x-1.5 active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        <span>+ Upload Dokumen</span>
                    </button>
                    <button @click="addDatasetModal = true" class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition">
                        + Tambah Dataset
                    </button>
                    <button @click="addEvidenceModal = true" class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition">
                        + Tambah Bukti (Evidence)
                    </button>
                </div>
            </div>

            <!-- Datasets Overview Strip -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse ($project->datasets as $dataset)
                    <div class="bg-white rounded-xl border border-slate-200/80 p-4 shadow-2xs space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-sky-50 text-sky-800 border border-sky-200">
                                {{ $dataset->source_type }}
                            </span>
                            <form method="POST" action="{{ route('projects.datasets.destroy', ['project' => $project->id, 'dataset' => $dataset->id]) }}" onsubmit="return confirm('Hapus dataset ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-slate-400 hover:text-rose-600">Hapus</button>
                            </form>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900">{{ $dataset->name }}</h3>
                        <p class="text-xs text-slate-500 line-clamp-2">{{ $dataset->description }}</p>
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                            <span>Records: <strong class="text-slate-700">{{ number_format($dataset->record_count) }}</strong> baris</span>
                            <span>Lokasi: <strong class="text-slate-700">{{ $dataset->location ?? 'Laboratorium' }}</strong></span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 py-6 text-center text-xs text-slate-400 bg-white rounded-xl border border-slate-200">
                        Belum ada dataset yang terdaftar.
                    </div>
                @endforelse
            </div>

            <!-- Evidences Cards List -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Daftar Evidence Terverifikasi ({{ $project->evidences->count() }})</h3>
                        <p class="text-[11px] text-slate-400">Setiap evidence dapat dihubungkan ke scientific claim untuk mencegah halusinasi.</p>
                    </div>
                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md">
                        Semua Bukti Dilacak ke Sumber
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse ($project->evidences as $ev)
                        @php
                            $linkedClaimsCount = $ev->claims->count();
                        @endphp
                        <div class="p-4 rounded-xl border border-slate-200/80 bg-white shadow-2xs space-y-3 hover:border-teal-300 transition">
                            <div class="flex items-center justify-between">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-teal-50 text-teal-800 border border-teal-200">
                                    {{ $ev->evidence_type }}
                                </span>
                                <div class="flex items-center space-x-2">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ $ev->quality_status === 'ground_truth' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                        {{ strtoupper($ev->quality_status) }}
                                    </span>
                                    <form method="POST" action="{{ route('projects.evidences.destroy', ['project' => $project->id, 'evidence' => $ev->id]) }}" onsubmit="return confirm('Hapus evidence ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-rose-600">×</button>
                                    </form>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-sm font-bold text-slate-900">{{ $ev->title }}</h4>
                                <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $ev->description }}</p>
                            </div>

                            @if (!empty($ev->data_payload))
                                <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-100 text-[11px] font-mono text-slate-700 space-y-1">
                                    @foreach ($ev->data_payload as $k => $v)
                                        <div class="flex justify-between">
                                            <span class="text-slate-400">{{ $k }}:</span>
                                            <span class="font-bold text-teal-800">{{ is_array($v) ? json_encode($v) : $v }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                                <span>Terhubung ke: <strong class="text-slate-800">{{ $linkedClaimsCount }} Klaim</strong></span>
                                <span>{{ $ev->collected_at ? $ev->collected_at->format('d M Y') : 'Baru' }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 py-8 text-center text-xs text-slate-400">
                            Belum ada bukti empiris yang diunggah. Klik tombol "+ Tambah Bukti (Evidence)" di atas.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Dokumen & Berkas Riset Section -->
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div>
                        <div class="flex items-center space-x-2">
                            <h3 class="text-sm font-bold text-slate-900">Dokumen & Berkas Proyek</h3>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-teal-100 text-teal-800">
                                {{ $project->documents->count() }} Berkas
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 mt-0.5">Arsip berkas proposal, ethical clearance, instrumen survei, spreadsheet data mentah, dan laporan.</p>
                    </div>
                    <button @click="addDocumentModal = true" class="self-start sm:self-auto px-3.5 py-1.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-teal-600 to-emerald-600 hover:opacity-95 shadow-xs transition flex items-center space-x-1.5 active:scale-95">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Upload Dokumen Baru</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse ($project->documents as $doc)
                        @php
                            $badge = $doc->category_badge;
                            $ext = strtolower($doc->file_extension);
                        @endphp
                        <div class="group p-4 rounded-xl border border-slate-200/90 bg-white hover:border-teal-300 hover:shadow-xs transition space-y-3 flex flex-col justify-between">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $badge['bg'] }} {{ $badge['text'] }} border {{ $badge['border'] }}">
                                        {{ $doc->category_label }}
                                    </span>
                                    <form method="POST" action="{{ route('projects.documents.destroy', ['project' => $project->id, 'document' => $doc->id]) }}" onsubmit="return confirm('Hapus dokumen {{ addslashes($doc->title) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Hapus berkas" class="text-slate-400 hover:text-rose-600 p-1 text-xs transition">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                                <div class="flex items-start space-x-3">
                                    <!-- File Icon badge based on extension -->
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 
                                        @if(in_array($ext, ['pdf'])) bg-rose-50 text-rose-600 border border-rose-100
                                        @elseif(in_array($ext, ['doc', 'docx'])) bg-blue-50 text-blue-600 border border-blue-100
                                        @elseif(in_array($ext, ['xls', 'xlsx', 'csv'])) bg-emerald-50 text-emerald-600 border border-emerald-100
                                        @elseif(in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'svg'])) bg-amber-50 text-amber-600 border border-amber-100
                                        @elseif(in_array($ext, ['zip', 'rar', '7z'])) bg-purple-50 text-purple-600 border border-purple-100
                                        @else bg-slate-100 text-slate-600 border border-slate-200
                                        @endif font-bold text-xs">
                                        @if(in_array($ext, ['pdf']))
                                            PDF
                                        @elseif(in_array($ext, ['doc', 'docx']))
                                            DOC
                                        @elseif(in_array($ext, ['xls', 'xlsx', 'csv']))
                                            XLS
                                        @elseif(in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                                            IMG
                                        @elseif(in_array($ext, ['zip', 'rar']))
                                            ZIP
                                        @else
                                            FILE
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-xs font-bold text-slate-900 leading-snug line-clamp-2" title="{{ $doc->title }}">{{ $doc->title }}</h4>
                                        <p class="text-[11px] text-slate-400 truncate mt-0.5" title="{{ $doc->file_name }}">{{ $doc->file_name }}</p>
                                    </div>
                                </div>

                                @if($doc->description)
                                    <p class="text-[11px] text-slate-600 line-clamp-2 bg-slate-50 p-2 rounded-lg border border-slate-100">{{ $doc->description }}</p>
                                @endif
                            </div>

                            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                                <span>{{ $doc->formatted_size }} • {{ $doc->created_at ? $doc->created_at->format('d M Y') : 'Baru' }}</span>
                                <div class="flex items-center space-x-1.5">
                                    <a href="{{ route('projects.documents.view', ['project' => $project->id, 'document' => $doc->id]) }}" target="_blank"
                                       class="px-2 py-1 rounded-md text-[11px] font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                                        Lihat
                                    </a>
                                    <a href="{{ route('projects.documents.download', ['project' => $project->id, 'document' => $doc->id]) }}"
                                       class="px-2 py-1 rounded-md text-[11px] font-semibold text-teal-800 bg-teal-50 hover:bg-teal-100 border border-teal-200 transition flex items-center space-x-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        <span>Unduh</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-8 text-center text-xs text-slate-400 bg-slate-50/60 rounded-xl border border-dashed border-slate-200 p-6 space-y-2">
                            <div class="w-10 h-10 mx-auto rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                            </div>
                            <div class="font-bold text-slate-700">Belum ada dokumen yang diunggah ke proyek ini.</div>
                            <p class="text-slate-400 max-w-sm mx-auto">Upload proposal penelitian, ethical clearance, instrumen survei, spreadsheet raw data, atau naskah draf awal.</p>
                            <button @click="addDocumentModal = true" class="mt-2 inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-white bg-teal-600 hover:bg-teal-700 transition">
                                <span>+ Upload Dokumen Sekarang</span>
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- ==================== TAB 5: ANALYSIS & CLAIMS (TRACEABILITY MATRIX) ==================== -->
        <div x-show="activeTab === 'analysis'" class="space-y-6 animate-fade-in" x-cloak>
            
            <!-- Anti-Hallucination & Traceability Pipeline Hero -->
            <div class="bg-gradient-to-r from-sky-50 via-teal-50 to-emerald-50 border border-teal-200/90 rounded-2xl p-5 shadow-2xs space-y-2">
                <div class="flex items-center space-x-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-teal-700 text-white">
                        Evidence → Finding → Claim → Paper Section
                    </span>
                    <span class="text-xs font-semibold text-slate-700">Mekanisme Keterlacakan Ilmiah (Traceability)</span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Setiap klaim yang dipublikasikan pada naskah ilmiah harus memiliki tautan langsung ke data empiris (evidence). Klaim tanpa dasar bukti akan ditandai dengan status <strong>Missing Evidence</strong> untuk mencegah halusinasi data.
                </p>
            </div>

            <!-- Two Cards: Findings & Scientific Claims -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                <!-- Findings (Derived Outcomes) -->
                <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Findings (Temuan Analisis)</h3>
                            <p class="text-[11px] text-slate-400">Hasil temuan dari pengolahan data mentah.</p>
                        </div>
                        <button @click="addFindingModal = true" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-sky-800 bg-sky-50 border border-sky-200 hover:bg-sky-100 transition">
                            + Tambah Finding
                        </button>
                    </div>

                    <div class="space-y-3">
                        @forelse ($project->findings as $finding)
                            <div class="p-3.5 rounded-xl border border-slate-200/80 bg-white space-y-2 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-sky-50 text-sky-800">
                                        {{ $finding->finding_type }}
                                    </span>
                                    <span class="text-[10px] font-bold text-teal-700">
                                        Confidence: {{ $finding->confidence_score }}%
                                    </span>
                                </div>
                                <h4 class="font-bold text-slate-900">{{ $finding->title }}</h4>
                                <p class="text-slate-600 text-xs leading-relaxed">{{ $finding->statement }}</p>
                                
                                @if (!empty($finding->metrics_summary))
                                    <div class="bg-slate-50 p-2 rounded text-[11px] font-mono text-slate-700 space-y-0.5">
                                        @foreach ($finding->metrics_summary as $mk => $mv)
                                            <div class="flex justify-between">
                                                <span class="text-slate-400">{{ $mk }}:</span>
                                                <span class="font-bold text-slate-800">{{ $mv }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-4 text-center">Belum ada temuan analisis.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Scientific Claims & Anti-Hallucination Mapping (7 cols) -->
                <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">Scientific Claims & Evidence Links</h3>
                            <p class="text-[11px] text-slate-400">Pernyataan ilmiah yang akan dimasukkan ke section paper.</p>
                        </div>
                        <button @click="addClaimModal = true" class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-white bg-gradient-to-r from-sky-600 to-teal-600 hover:opacity-95 transition shadow-sm">
                            + Tambah Scientific Claim
                        </button>
                    </div>

                    <div class="space-y-4">
                        @forelse ($project->claims as $claim)
                            @php
                                $isGrounded = $claim->anti_hallucination_status === 'grounded' && $claim->evidences->count() > 0;
                            @endphp
                            <div class="p-4 rounded-xl border {{ $isGrounded ? 'border-emerald-200/80 bg-white' : 'border-rose-200 bg-rose-50/30' }} space-y-3 text-xs shadow-2xs transition">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        @if ($isGrounded)
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-300 flex items-center space-x-1">
                                                <svg class="w-3 h-3 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                <span>Grounded in Evidence</span>
                                            </span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-rose-100 text-rose-800 border border-rose-300 animate-pulse flex items-center space-x-1">
                                                <svg class="w-3 h-3 text-rose-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                                <span>Missing Evidence (Anti-Hallucination Alert)</span>
                                            </span>
                                        @endif
                                        <span class="text-[10px] text-slate-400">• Target: <strong class="text-slate-700 capitalize">{{ $claim->section_target }}</strong></span>
                                    </div>

                                    <form method="POST" action="{{ route('projects.claims.destroy', ['project' => $project->id, 'claim' => $claim->id]) }}" onsubmit="return confirm('Hapus klaim ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-rose-600">Hapus</button>
                                    </form>
                                </div>

                                <p class="text-sm font-bold text-slate-900 leading-snug">
                                    "{{ $claim->claim_text }}"
                                </p>

                                <!-- Linked Evidences Pills -->
                                <div class="space-y-1.5 pt-2 border-t border-slate-100">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tautan Bukti Empiris (Evidence Backlinks):</span>
                                    @if ($claim->evidences->count() > 0)
                                        <div class="space-y-1">
                                            @foreach ($claim->evidences as $ev)
                                                <div class="flex items-center justify-between bg-emerald-50/70 border border-emerald-200/70 px-2.5 py-1.5 rounded-lg text-[11px] text-emerald-900">
                                                    <span class="font-medium">[{{ $ev->evidence_type }}] {{ $ev->title }}</span>
                                                    <form method="POST" action="{{ route('projects.claims.unlink-evidence', ['project' => $project->id, 'claim' => $claim->id, 'evidence' => $ev->id]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-slate-400 hover:text-rose-600 text-[10px]">Lepas</button>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-rose-600 italic text-[11px]">Belum ada bukti yang ditautkan ke klaim ini. Klaim ini tidak sah menurut kaidah ilmiah Research OS.</p>
                                    @endif
                                </div>

                                <!-- Action: Link Evidence to this claim -->
                                <div class="pt-2 flex justify-end">
                                    <button @click="activeClaimId = {{ $claim->id }}; linkEvidenceModal = true" class="px-3 py-1 rounded-lg text-xs font-bold text-teal-800 bg-teal-50 border border-teal-200 hover:bg-teal-100 transition">
                                        + Tautkan Bukti (Evidence) ke Klaim Ini
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 py-6 text-center">Belum ada klaim ilmiah yang dirumuskan.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

        <!-- ==================== TAB 6: PAPER BUILDER ==================== -->
        <div x-show="activeTab === 'paper'" class="space-y-6 animate-fade-in" x-cloak>
            
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Paper Builder & Evidence Synthesizer</h2>
                    <p class="text-xs text-slate-500">Susun draf naskah paper ilmiah berdasarkan data yang benar-benar tersedia, bebas dari angka rekaan AI.</p>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('projects.paper.print', $project->id) }}" target="_blank" class="px-3 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition shadow-2xs flex items-center space-x-1.5">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        <span>Cetak / PDF Layout Akademik</span>
                    </a>
                    <a href="{{ route('projects.paper.export-markdown', $project->id) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 transition shadow-sm flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Unduh Markdown (.md)</span>
                    </a>
                </div>
            </div>

            <!-- Paper Structure Accordions / Editors -->
            <div class="space-y-4">
                @foreach ($project->paperSections as $sec)
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-3" x-data="{ expanded: true }">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <button @click="expanded = !expanded" class="text-slate-400 hover:text-slate-600">
                                    <span x-text="expanded ? '▼' : '▶'" class="text-xs"></span>
                                </button>
                                <h3 class="text-sm font-extrabold text-slate-900">{{ $sec->title }}</h3>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded {{ $sec->is_drafted ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $sec->is_drafted ? 'Draft Ready (' . $sec->word_count . ' kata)' : 'Belum Didraf' }}
                                </span>
                            </div>

                            <div class="flex items-center space-x-2">
                                <form method="POST" action="{{ route('projects.paper-sections.draft', ['project' => $project->id, 'section' => $sec->id]) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 rounded-lg text-xs font-bold text-teal-800 bg-teal-50 border border-teal-200 hover:bg-teal-100 transition flex items-center space-x-1.5">
                                        <svg class="w-3.5 h-3.5 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        <span>Draf AI Berbasis Bukti</span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Section Editor / Viewer -->
                        <div x-show="expanded" class="pt-2 border-t border-slate-100 space-y-3">
                            <form method="POST" action="{{ route('projects.paper-sections.update', ['project' => $project->id, 'section' => $sec->id]) }}">
                                @csrf
                                @method('PUT')
                                <textarea name="content" rows="6" class="w-full text-xs font-mono rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 bg-[#fbfcfd] p-3 leading-relaxed">{{ $sec->content }}</textarea>
                                
                                <div class="flex items-center justify-between pt-2">
                                    <span class="text-[11px] text-slate-400">Gunakan format akademik standar atau Markdown.</span>
                                    <button type="submit" class="px-4 py-1.5 rounded-lg text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 transition">
                                        Simpan Perubahan Section
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        <!-- ==================== TAB 7: MULTI-OUTPUT & PUBLICATION TRACKER ==================== -->
        <div x-show="activeTab === 'outputs'" class="space-y-6 animate-fade-in" x-cloak>
            
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Multi-Output Engine & Publication Tracker</h2>
                    <p class="text-xs text-slate-500">Satu proyek riset dapat menghasilkan banyak jenis luaran ilmiah tanpa menduplikasi data.</p>
                </div>
                <button @click="addOutputModal = true" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-sky-600 to-teal-600 hover:opacity-95 transition shadow-sm">
                    + Tambah Target Luaran
                </button>
            </div>

            <!-- Outputs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @forelse ($project->outputs as $out)
                    @php
                        $statusBadge = match($out->status) {
                            'idea' => 'bg-slate-100 text-slate-700',
                            'drafting' => 'bg-sky-50 text-sky-800 border-sky-200',
                            'submitted' => 'bg-amber-50 text-amber-800 border-amber-200',
                            'under_review' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                            'revision' => 'bg-purple-50 text-purple-800 border-purple-200',
                            'accepted' => 'bg-teal-50 text-teal-800 border-teal-200',
                            'published' => 'bg-emerald-100 text-emerald-900 border-emerald-300 font-extrabold',
                            default => 'bg-slate-100 text-slate-700',
                        };
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-3 hover:border-teal-300 transition flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700">
                                    {{ str_replace('_', ' ', strtoupper($out->output_type)) }}
                                </span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border {{ $statusBadge }}">
                                    {{ strtoupper($out->status) }}
                                </span>
                            </div>

                            <h3 class="text-sm font-bold text-slate-900 leading-snug">{{ $out->title }}</h3>

                            <div class="text-xs text-slate-500 space-y-1">
                                <p><strong>Target Venue:</strong> {{ $out->target_venue ?? 'Belum ditentukan' }}</p>
                                @if ($out->indexing)
                                    <p><strong>Indexing:</strong> <span class="text-teal-700 font-bold">{{ $out->indexing }}</span></p>
                                @endif
                                @if ($out->doi_or_url)
                                    <p><strong>DOI / Link:</strong> <a href="{{ $out->doi_or_url }}" target="_blank" class="text-sky-600 underline">{{ $out->doi_or_url }}</a></p>
                                @endif
                            </div>

                            @if ($out->notes)
                                <p class="text-[11px] text-slate-500 bg-slate-50 p-2.5 rounded-lg border border-slate-100">
                                    <strong>Catatan:</strong> {{ $out->notes }}
                                </p>
                            @endif
                        </div>

                        <!-- Update status inline -->
                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                            <form method="POST" action="{{ route('projects.outputs.update', ['project' => $project->id, 'output' => $out->id]) }}" class="flex items-center space-x-2">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="title" value="{{ $out->title }}">
                                <input type="hidden" name="output_type" value="{{ $out->output_type }}">
                                <select name="status" onchange="this.form.submit()" class="text-[11px] font-bold rounded-lg border-slate-200 py-1 pl-2 pr-6">
                                    <option value="drafting" {{ $out->status === 'drafting' ? 'selected' : '' }}>Drafting</option>
                                    <option value="submitted" {{ $out->status === 'submitted' ? 'selected' : '' }}>Submitted</option>
                                    <option value="under_review" {{ $out->status === 'under_review' ? 'selected' : '' }}>Under Review</option>
                                    <option value="revision" {{ $out->status === 'revision' ? 'selected' : '' }}>Revision</option>
                                    <option value="accepted" {{ $out->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                                    <option value="published" {{ $out->status === 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </form>

                            <form method="POST" action="{{ route('projects.outputs.destroy', ['project' => $project->id, 'output' => $out->id]) }}" onsubmit="return confirm('Hapus luaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-slate-400 hover:text-rose-600">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 py-8 text-center text-xs text-slate-400 bg-white rounded-2xl border border-slate-200">
                        Belum ada luaran yang ditambahkan.
                    </div>
                @endforelse
            </div>

        </div>

        <!-- ==================== TAB 8: AI ASSISTANT & REVIEWER ==================== -->
        <div x-show="activeTab === 'ai'" class="space-y-6 animate-fade-in" x-cloak>
            
            <div class="bg-gradient-to-r from-purple-50 via-sky-50 to-teal-50 border border-purple-200/80 rounded-2xl p-6 shadow-2xs space-y-3">
                <div class="flex items-center space-x-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-600 animate-ping"></span>
                    <h2 class="text-base font-extrabold text-slate-900">AI Research Assistant & Peer Reviewer Mode</h2>
                </div>
                <p class="text-xs text-slate-600 max-w-4xl leading-relaxed">
                    Asisten riset cerdas yang dirancang untuk memperkuat validitas ilmiah, memetakan celah penelitian, menyintesis literatur, hingga bertindak sebagai <strong>Peer Reviewer</strong> yang menguji klaim dan menandai bagian tanpa bukti.
                </p>
            </div>

            <!-- Assistant Mode Selector Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
                <form method="POST" action="{{ route('projects.ai.query', $project->id) }}">
                    @csrf
                    <input type="hidden" name="mode" value="research_mapper">
                    <button type="submit" class="w-full text-left p-3.5 rounded-xl border border-slate-200/80 bg-white hover:border-sky-300 transition shadow-2xs space-y-2">
                        <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        </div>
                        <div>
                            <div class="font-bold text-xs text-slate-900">Research Mapper</div>
                            <p class="text-[10px] text-slate-400 mt-0.5">Petakan problem, RQ, novelty & kontribusi</p>
                        </div>
                    </button>
                </form>

                <form method="POST" action="{{ route('projects.ai.query', $project->id) }}">
                    @csrf
                    <input type="hidden" name="mode" value="literature_assistant">
                    <button type="submit" class="w-full text-left p-3.5 rounded-xl border border-slate-200/80 bg-white hover:border-teal-300 transition shadow-2xs space-y-2">
                        <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div>
                            <div class="font-bold text-xs text-slate-900">Literature Synthesizer</div>
                            <p class="text-[10px] text-slate-400 mt-0.5">Sintesis matriks literatur & gap</p>
                        </div>
                    </button>
                </form>

                <form method="POST" action="{{ route('projects.ai.query', $project->id) }}">
                    @csrf
                    <input type="hidden" name="mode" value="methodology_assistant">
                    <button type="submit" class="w-full text-left p-3.5 rounded-xl border border-slate-200/80 bg-white hover:border-teal-300 transition shadow-2xs space-y-2">
                        <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <div class="font-bold text-xs text-slate-900">Methodology Consultant</div>
                            <p class="text-[10px] text-slate-400 mt-0.5">Audit variabel, sampel & metrik evaluasi</p>
                        </div>
                    </button>
                </form>

                <form method="POST" action="{{ route('projects.ai.query', $project->id) }}">
                    @csrf
                    <input type="hidden" name="mode" value="data_assistant">
                    <button type="submit" class="w-full text-left p-3.5 rounded-xl border border-slate-200/80 bg-white hover:border-sky-300 transition shadow-2xs space-y-2">
                        <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <div>
                            <div class="font-bold text-xs text-slate-900">Data & Findings Explorer</div>
                            <p class="text-[10px] text-slate-400 mt-0.5">Deteksi pola data & korelasi anomali</p>
                        </div>
                    </button>
                </form>

                <form method="POST" action="{{ route('projects.ai.query', $project->id) }}">
                    @csrf
                    <input type="hidden" name="mode" value="reviewer_mode">
                    <button type="submit" class="w-full text-left p-3.5 rounded-xl border border-purple-200 bg-purple-50/50 hover:bg-purple-50 transition shadow-2xs space-y-2">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <div class="font-bold text-xs text-purple-900">Reviewer Mode (Audit)</div>
                            <p class="text-[10px] text-purple-700 mt-0.5">Audit Anti-Halusinasi & kelemahan klaim</p>
                        </div>
                    </button>
                </form>
            </div>

            <!-- Active AI Result Output Box -->
            @if (session('ai_result'))
                @php $res = session('ai_result'); @endphp
                <div class="bg-white rounded-2xl border border-slate-200/90 p-6 shadow-2xs space-y-4 animate-fade-in">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center space-x-2">
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-purple-100 text-purple-800">
                                {{ $res['title'] ?? 'AI Research Response' }}
                            </span>
                        </div>
                        <span class="text-xs text-slate-400">Grounded Research AI</span>
                    </div>

                    @if (!empty($res['problem_refinement']))
                        <div class="space-y-2 text-xs">
                            <div class="p-3 rounded-xl bg-sky-50/60 border border-sky-100 text-sky-950">
                                <strong>Problem Refinement:</strong> {{ $res['problem_refinement'] }}
                            </div>
                            <div class="p-3 rounded-xl bg-teal-50/60 border border-teal-100 text-teal-950">
                                <strong>Research Gap Suggestion:</strong> {{ $res['gap_suggestion'] }}
                            </div>
                            <div class="p-3 rounded-xl bg-emerald-50/60 border border-emerald-100 text-emerald-950">
                                <strong>Novelty Strengthening:</strong> {{ $res['novelty_strengthening'] }}
                            </div>
                        </div>
                    @endif

                    @if (!empty($res['claims_audit']))
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-xs bg-slate-50 p-3 rounded-xl">
                                <span>Total Klaim Diperiksa: <strong>{{ $res['claims_checked'] }}</strong></span>
                                <span class="text-emerald-700">Grounded: <strong>{{ $res['grounded_count'] }}</strong></span>
                                <span class="text-rose-700">Missing Evidence: <strong>{{ $res['unbacked_count'] }}</strong></span>
                            </div>

                            <div class="space-y-2">
                                @foreach ($res['claims_audit'] as $ca)
                                    <div class="p-3 rounded-xl border {{ $ca['status'] === 'grounded' ? 'border-emerald-200 bg-emerald-50/30' : 'border-rose-200 bg-rose-50/40' }} text-xs">
                                        <p class="font-bold text-slate-900">"{{ $ca['claim'] }}"</p>
                                        <p class="mt-1 text-[11px] {{ $ca['status'] === 'grounded' ? 'text-emerald-800 font-semibold' : 'text-rose-800 font-bold' }}">
                                            {{ $ca['verdict'] }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if (!empty($res['synthesis_paragraph']))
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 leading-relaxed">
                            <strong class="block text-slate-900 mb-1">Literature Synthesis Draft:</strong>
                            {{ $res['synthesis_paragraph'] }}
                        </div>
                    @endif
                </div>
            @endif

        </div>

        <!-- ==================== MODALS (MOBILE-FRIENDLY BOTTOM SHEETS) ==================== -->

        <!-- 1. Modal: Tambah Research Question -->
        <div x-show="addQuestionModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4" x-cloak>
            <div class="bg-white rounded-t-3xl sm:rounded-2xl max-w-lg w-full p-5 sm:p-6 space-y-4 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto">
                <div class="sm:hidden w-12 h-1.5 bg-slate-200 rounded-full mx-auto -mt-1 mb-2"></div>
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Tambah Research Question</h3>
                    <button @click="addQuestionModal = false" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-900 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('projects.questions.store', $project->id) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pertanyaan Penelitian (RQ) *</label>
                        <textarea name="question" required rows="2" placeholder="Bagaimana performa akurasi sensor dibandingkan metode baku..." class="w-full text-xs rounded-xl border-slate-200"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Objektif Penelitian</label>
                        <textarea name="objective" rows="2" placeholder="Tujuan spesifik yang ingin dicapai..." class="w-full text-xs rounded-xl border-slate-200"></textarea>
                    </div>
                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="addQuestionModal = false" class="px-3 py-1.5 text-xs text-slate-500">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold active:scale-95 transition">Simpan RQ</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 2. Modal: Tambah Dataset -->
        <div x-show="addDatasetModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4" x-cloak>
            <div class="bg-white rounded-t-3xl sm:rounded-2xl max-w-lg w-full p-5 sm:p-6 space-y-4 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto">
                <div class="sm:hidden w-12 h-1.5 bg-slate-200 rounded-full mx-auto -mt-1 mb-2"></div>
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Tambah Dataset Baru</h3>
                    <button @click="addDatasetModal = false" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-900 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('projects.datasets.store', $project->id) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Dataset *</label>
                        <input type="text" name="name" required placeholder="Contoh: Log Telemetri Kelembapan Subak 30 Hari" class="w-full text-xs rounded-xl border-slate-200">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tipe Sumber</label>
                            <select name="source_type" class="w-full text-xs rounded-xl border-slate-200">
                                <option value="sensor">Sensor Telemetri</option>
                                <option value="experiment">Uji Laboratorium</option>
                                <option value="survey">Survei Lapangan</option>
                                <option value="interview">Wawancara</option>
                                <option value="benchmark">Public Benchmark</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Records</label>
                            <input type="number" name="record_count" placeholder="14400" class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Dataset</label>
                        <textarea name="description" rows="2" class="w-full text-xs rounded-xl border-slate-200"></textarea>
                    </div>
                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="addDatasetModal = false" class="px-3 py-1.5 text-xs text-slate-500">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold active:scale-95 transition">Simpan Dataset</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 3. Modal: Tambah Evidence -->
        <div x-show="addEvidenceModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4" x-cloak>
            <div class="bg-white rounded-t-3xl sm:rounded-2xl max-w-lg w-full p-5 sm:p-6 space-y-4 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto">
                <div class="sm:hidden w-12 h-1.5 bg-slate-200 rounded-full mx-auto -mt-1 mb-2"></div>
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Tambah Bukti Empiris (Evidence)</h3>
                    <button @click="addEvidenceModal = false" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-900 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('projects.evidences.store', $project->id) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul Bukti (Evidence Title) *</label>
                        <input type="text" name="title" required placeholder="Contoh: Kurva Kalibrasi Sensor R² = 0.984 vs Oven ISO" class="w-full text-xs rounded-xl border-slate-200">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tipe Bukti</label>
                            <select name="evidence_type" class="w-full text-xs rounded-xl border-slate-200">
                                <option value="data_point">Data Point / Statistik</option>
                                <option value="sensor_reading">Pembacaan Sensor</option>
                                <option value="experiment_metric">Metrik Eksperimen</option>
                                <option value="test_log">Log Pengujian</option>
                                <option value="photo">Foto Dokumentasi</option>
                                <option value="quote">Kutipan Wawancara</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Kualitas Bukti</label>
                            <select name="quality_status" class="w-full text-xs rounded-xl border-slate-200">
                                <option value="ground_truth">Baku Mutu (Ground Truth)</option>
                                <option value="verified" selected>Terverifikasi (Verified)</option>
                                <option value="unverified">Belum Diverifikasi</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi & Rincian Bukti *</label>
                        <textarea name="description" required rows="2" placeholder="Uraikan nilai kuantitatif atau temuan faktual bukti ini..." class="w-full text-xs rounded-xl border-slate-200"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Key Metrik (Opsional)</label>
                            <input type="text" name="metric_key" placeholder="Misal: r_squared" class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nilai Metrik</label>
                            <input type="text" name="metric_value" placeholder="Misal: 0.984" class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="addEvidenceModal = false" class="px-3 py-1.5 text-xs text-slate-500">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-sky-600 to-teal-600 text-white rounded-xl text-xs font-bold active:scale-95 transition">Simpan Evidence</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 4. Modal: Tambah Scientific Claim -->
        <div x-show="addClaimModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4" x-cloak>
            <div class="bg-white rounded-t-3xl sm:rounded-2xl max-w-lg w-full p-5 sm:p-6 space-y-4 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto">
                <div class="sm:hidden w-12 h-1.5 bg-slate-200 rounded-full mx-auto -mt-1 mb-2"></div>
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Tambah Scientific Claim</h3>
                    <button @click="addClaimModal = false" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-900 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('projects.claims.store', $project->id) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pernyataan Klaim Ilmiah *</label>
                        <textarea name="claim_text" required rows="3" placeholder="Pernyataan yang dapat dipertanggungjawabkan secara ilmiah..." class="w-full text-xs rounded-xl border-slate-200"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Target Section Paper</label>
                            <select name="section_target" class="w-full text-xs rounded-xl border-slate-200">
                                <option value="results">Results</option>
                                <option value="discussion">Discussion</option>
                                <option value="introduction">Introduction</option>
                                <option value="conclusion">Conclusion</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tautkan ke Evidence</label>
                            <select name="evidence_id" class="w-full text-xs rounded-xl border-slate-200">
                                <option value="">-- Pilih Evidence --</option>
                                @foreach ($project->evidences as $e)
                                    <option value="{{ $e->id }}">{{ Str::limit($e->title, 35) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="addClaimModal = false" class="px-3 py-1.5 text-xs text-slate-500">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold active:scale-95 transition">Simpan Klaim</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 5. Modal: Link Evidence to Existing Claim -->
        <div x-show="linkEvidenceModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4" x-cloak>
            <div class="bg-white rounded-t-3xl sm:rounded-2xl max-w-md w-full p-5 sm:p-6 space-y-4 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto">
                <div class="sm:hidden w-12 h-1.5 bg-slate-200 rounded-full mx-auto -mt-1 mb-2"></div>
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Tautkan Bukti Empiris ke Klaim</h3>
                    <button @click="linkEvidenceModal = false" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-900 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form :action="'/projects/{{ $project->id }}/claims/' + activeClaimId + '/link-evidence'" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Bukti (Evidence) Pendukung *</label>
                        <select name="evidence_id" required class="w-full text-xs rounded-xl border-slate-200">
                            @foreach ($project->evidences as $e)
                                <option value="{{ $e->id }}">[{{ $e->evidence_type }}] {{ $e->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Relevansi (Opsional)</label>
                        <input type="text" name="relevance_note" placeholder="Alasan bukti ini mendukung klaim..." class="w-full text-xs rounded-xl border-slate-200">
                    </div>
                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="linkEvidenceModal = false" class="px-3 py-1.5 text-xs text-slate-500">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-sky-600 to-teal-600 text-white rounded-xl text-xs font-bold active:scale-95 transition">Verifikasi Klaim (Grounded)</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 6. Modal: Tambah Finding -->
        <div x-show="addFindingModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4" x-cloak>
            <div class="bg-white rounded-t-3xl sm:rounded-2xl max-w-lg w-full p-5 sm:p-6 space-y-4 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto">
                <div class="sm:hidden w-12 h-1.5 bg-slate-200 rounded-full mx-auto -mt-1 mb-2"></div>
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Tambah Finding (Temuan)</h3>
                    <button @click="addFindingModal = false" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-900 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('projects.findings.store', $project->id) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul Temuan *</label>
                        <input type="text" name="title" required placeholder="Contoh: Koreksi Polinomial Menekan Galat Menjadi 1.82%" class="w-full text-xs rounded-xl border-slate-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pernyataan Temuan *</label>
                        <textarea name="statement" required rows="2" class="w-full text-xs rounded-xl border-slate-200"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tipe Temuan</label>
                            <select name="finding_type" class="w-full text-xs rounded-xl border-slate-200">
                                <option value="quantitative">Kuantitatif</option>
                                <option value="comparative">Komparatif</option>
                                <option value="qualitative">Kualitatif</option>
                                <option value="anomalous">Anomali</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Confidence Score (%)</label>
                            <input type="number" name="confidence_score" value="90" min="1" max="100" class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                    </div>
                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="addFindingModal = false" class="px-3 py-1.5 text-xs text-slate-500">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold active:scale-95 transition">Simpan Finding</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 7. Modal: Tambah Target Luaran (Output) -->
        <div x-show="addOutputModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4" x-cloak>
            <div class="bg-white rounded-t-3xl sm:rounded-2xl max-w-lg w-full p-5 sm:p-6 space-y-4 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto">
                <div class="sm:hidden w-12 h-1.5 bg-slate-200 rounded-full mx-auto -mt-1 mb-2"></div>
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Tambah Target Luaran Penelitian</h3>
                    <button @click="addOutputModal = false" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-900 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('projects.outputs.store', $project->id) }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul Luaran / Naskah *</label>
                        <input type="text" name="title" required placeholder="Contoh: Low-Power Edge-IoT Sensor Mesh in Subak..." class="w-full text-xs rounded-xl border-slate-200">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Jenis Luaran</label>
                            <select name="output_type" class="w-full text-xs rounded-xl border-slate-200">
                                <option value="journal_manuscript">Naskah Jurnal</option>
                                <option value="conference_paper">Paper Konferensi</option>
                                <option value="community_service">Laporan Pengabdian (PKM)</option>
                                <option value="dataset_repository">Dataset Repository (Zenodo)</option>
                                <option value="technical_report">Technical Report / Blueprint</option>
                                <option value="policy_brief">Policy Brief</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Status</label>
                            <select name="status" class="w-full text-xs rounded-xl border-slate-200">
                                <option value="drafting">Drafting</option>
                                <option value="submitted">Submitted</option>
                                <option value="under_review">Under Review</option>
                                <option value="revision">Revision</option>
                                <option value="accepted">Accepted</option>
                                <option value="published">Published</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Target Venue / Jurnal</label>
                            <input type="text" name="target_venue" placeholder="IEEE IoT Journal..." class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Indexing</label>
                            <input type="text" name="indexing" placeholder="Scopus Q1, Sinta 2..." class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">DOI / URL Publikasi</label>
                        <input type="text" name="doi_or_url" placeholder="https://doi.org/..." class="w-full text-xs rounded-xl border-slate-200">
                    </div>
                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="addOutputModal = false" class="px-3 py-1.5 text-xs text-slate-500">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold active:scale-95 transition">Simpan Luaran</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 8. Modal: Upload Dokumen Riset -->
        <div x-show="addDocumentModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/50 backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4" x-cloak>
            <div class="bg-white rounded-t-3xl sm:rounded-2xl max-w-lg w-full p-5 sm:p-6 space-y-4 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto">
                <div class="sm:hidden w-12 h-1.5 bg-slate-200 rounded-full mx-auto -mt-1 mb-2"></div>
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Upload Dokumen Riset</h3>
                        <p class="text-[11px] text-slate-400">Unggah berkas pendukung riset ke workspace proyek</p>
                    </div>
                    <button @click="addDocumentModal = false" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:text-slate-900 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('projects.documents.store', $project->id) }}" enctype="multipart/form-data" class="space-y-3.5">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul Dokumen *</label>
                        <input type="text" name="title" required placeholder="Contoh: Proposal Riset Dikti 2026 / Ethical Approval" class="w-full text-xs rounded-xl border-slate-200 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kategori Dokumen *</label>
                        <select name="document_category" required class="w-full text-xs rounded-xl border-slate-200 focus:ring-teal-500 focus:border-teal-500">
                            <option value="proposal">Proposal & Ethical Clearance</option>
                            <option value="instrument">Instrumen Penelitian / Kuesioner</option>
                            <option value="raw_data">Raw Data / Spreadsheet (CSV, Excel)</option>
                            <option value="photo_evidence">Foto & Bukti Lapangan</option>
                            <option value="draft_report">Draf Naskah & Laporan Kemajuan</option>
                            <option value="literature">Referensi Jurnal / Paper Tambahan</option>
                            <option value="other">Dokumen Umum Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Pilih Berkas File *</label>
                        <input type="file" name="file" required class="w-full text-xs rounded-xl border border-slate-200 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                        <p class="text-[10px] text-slate-400 mt-1">Mendukung PDF, Word (DOCX), Excel/CSV, Gambar (JPG, PNG), dan ZIP hingga 25 MB.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi / Catatan Tambahan (Opsional)</label>
                        <textarea name="description" rows="2" placeholder="Catatan mengenai versi, isi berkas, atau nomor dokumen..." class="w-full text-xs rounded-xl border-slate-200 focus:ring-teal-500 focus:border-teal-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-2 pt-2 border-t border-slate-100">
                        <button type="button" @click="addDocumentModal = false" class="px-3.5 py-2 text-xs text-slate-500 hover:text-slate-800">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-teal-600 to-emerald-600 text-white rounded-xl text-xs font-bold shadow-sm hover:opacity-95 active:scale-95 transition flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <span>Unggah Dokumen</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ==================== MOBILE FLOATING STAGE STEPPER (SUPER EASY 1-THUMB WORKFLOW) ==================== -->
        <div class="sm:hidden fixed bottom-18 inset-x-3 z-30 flex items-center justify-between p-2 rounded-2xl bg-white/95 backdrop-blur-md border border-slate-200/90 shadow-xl shadow-slate-900/10 transition-all">
            <button type="button" @click="goToTab(prevTab)" :disabled="!prevTab"
                    :class="!prevTab ? 'opacity-30 pointer-events-none' : 'active:scale-90 text-slate-700 hover:text-teal-700'"
                    class="px-3 py-1.5 rounded-xl bg-slate-100 flex items-center space-x-1 text-xs font-bold transition">
                <span>‹</span>
                <span x-text="prevTab ? tabLabels[prevTab] : 'Awal'"></span>
            </button>

            <div class="text-center px-1">
                <div class="text-[11px] font-extrabold text-teal-800" x-text="tabLabels[activeTab]"></div>
                <div class="text-[9px] text-slate-400 font-medium">Tahap <span x-text="currentStepIndex + 1"></span> dari 8</div>
            </div>

            <button type="button" @click="goToTab(nextTab)" :disabled="!nextTab"
                    :class="!nextTab ? 'opacity-30 pointer-events-none' : 'active:scale-90 text-white bg-gradient-to-r from-sky-600 via-teal-600 to-emerald-600 shadow-sm'"
                    class="px-3 py-1.5 rounded-xl flex items-center space-x-1 text-xs font-bold transition">
                <span x-text="nextTab ? tabLabels[nextTab] : 'Selesai'"></span>
                <span>›</span>
            </button>
        </div>

    </div>
</x-app-layout>
