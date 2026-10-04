<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200/80">
            <div>
                <div class="flex items-center space-x-2 text-xs font-semibold text-teal-600">
                    <span class="tracking-wide uppercase">Modul 2 &middot; Research Projects</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">Daftar Paper & Proyek Riset</h1>
                <p class="text-xs text-slate-500">Kelola seluruh paper penelitian Anda. Buka Workspace untuk kelola data/bukti, atau langsung masuk ke Paper Builder untuk menulis naskah.</p>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" @click="tutorialModal = true" class="inline-flex items-center px-3.5 py-2 rounded-xl text-xs font-bold text-teal-800 bg-teal-50 border border-teal-200/90 hover:bg-teal-100 transition active:scale-95 space-x-1.5">
                    <svg class="w-4 h-4 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>Panduan Alur Riset</span>
                </button>
                <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-sky-600 to-teal-600 hover:opacity-95 shadow-sm transition">
                    + Buat Proyek Baru
                </a>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-2xs">
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('projects.index') }}" class="px-3 py-1 rounded-lg text-xs font-semibold {{ !request('status') ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'text-slate-600 hover:bg-slate-50' }}">
                    Semua Status
                </a>
                <a href="{{ route('projects.index', ['status' => 'drafting']) }}" class="px-3 py-1 rounded-lg text-xs font-semibold {{ request('status') === 'drafting' ? 'bg-slate-100 text-slate-800' : 'text-slate-600 hover:bg-slate-50' }}">
                    Drafting Foundation
                </a>
                <a href="{{ route('projects.index', ['status' => 'data_collection']) }}" class="px-3 py-1 rounded-lg text-xs font-semibold {{ request('status') === 'data_collection' ? 'bg-amber-50 text-amber-800' : 'text-slate-600 hover:bg-slate-50' }}">
                    Data Collection
                </a>
                <a href="{{ route('projects.index', ['status' => 'analysis']) }}" class="px-3 py-1 rounded-lg text-xs font-semibold {{ request('status') === 'analysis' ? 'bg-sky-50 text-sky-800' : 'text-slate-600 hover:bg-slate-50' }}">
                    Analysis & Claims
                </a>
                <a href="{{ route('projects.index', ['status' => 'paper_writing']) }}" class="px-3 py-1 rounded-lg text-xs font-semibold {{ request('status') === 'paper_writing' ? 'bg-teal-50 text-teal-800' : 'text-slate-600 hover:bg-slate-50' }}">
                    Paper Builder
                </a>
            </div>
            <div class="text-xs text-slate-500">
                Total: <strong>{{ $projects->total() }}</strong> proyek aktif
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="space-y-4">
            @forelse ($projects as $project)
                @php
                    $readiness = $project->readiness_breakdown;
                    $statusBadge = match($project->status) {
                        'drafting' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'label' => 'Drafting Foundation'],
                        'data_collection' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-800', 'label' => 'Data Collection'],
                        'analysis' => ['bg' => 'bg-sky-50', 'text' => 'text-sky-800', 'label' => 'Analysis & Claims'],
                        'paper_writing' => ['bg' => 'bg-teal-50', 'text' => 'text-teal-800', 'label' => 'Paper Builder'],
                        'under_review' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-800', 'label' => 'Under Review'],
                        'published' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-800', 'label' => 'Published'],
                        default => ['bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'label' => ucfirst($project->status)],
                    };
                @endphp
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs hover:border-teal-300 transition flex flex-col md:flex-row md:items-center justify-between gap-5 group">
                    <div class="space-y-2 flex-1">
                        <div class="flex items-center space-x-2">
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }}">
                                {{ $statusBadge['label'] }}
                            </span>
                            <span class="text-xs font-semibold text-teal-700">{{ $project->field }}</span>
                            @if ($project->target_deadline)
                                <span class="text-[11px] text-slate-400">• Deadline: {{ $project->target_deadline->format('d M Y') }}</span>
                            @endif
                        </div>

                        <h2 class="text-lg font-bold text-slate-900 group-hover:text-teal-700 transition">
                            <a href="{{ route('projects.show', $project->id) }}">
                                {{ $project->title }}
                            </a>
                        </h2>

                        <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                            {{ $project->summary ?? $project->problem_statement ?? 'Belum ada ringkasan proyek.' }}
                        </p>

                        <!-- Project Metrics Strip -->
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 pt-1">
                            <div class="flex items-center space-x-1">
                                <span class="text-slate-400">Research Questions:</span>
                                <strong class="text-slate-800">{{ $project->questions->count() }}</strong>
                            </div>
                            <div class="flex items-center space-x-1">
                                <span class="text-slate-400">Datasets:</span>
                                <strong class="text-slate-800">{{ $project->datasets->count() }}</strong>
                            </div>
                            <div class="flex items-center space-x-1">
                                <span class="text-slate-400">Bukti (Evidences):</span>
                                <strong class="text-teal-700 font-bold">{{ $project->evidences->count() }}</strong>
                            </div>
                            <div class="flex items-center space-x-1">
                                <span class="text-slate-400">Claims:</span>
                                <strong class="text-slate-800">{{ $project->claims->count() }}</strong>
                            </div>
                            <div class="flex items-center space-x-1">
                                <span class="text-slate-400">Target Luaran:</span>
                                <strong class="text-slate-800">{{ $project->outputs->count() }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Readiness & Action -->
                    <div class="flex md:flex-col items-center md:items-end justify-between gap-3 shrink-0 border-t md:border-t-0 pt-3 md:pt-0 border-slate-100">
                        <div class="text-left md:text-right">
                            <div class="flex items-baseline space-x-1">
                                <span class="text-xl font-black text-teal-700">{{ $readiness['overall'] }}%</span>
                                <span class="text-[10px] uppercase font-bold text-slate-400">Readiness</span>
                            </div>
                            <div class="w-32 bg-slate-100 rounded-full h-2 mt-1 overflow-hidden">
                                <div class="bg-gradient-to-r from-sky-500 to-teal-500 h-2 rounded-full" style="width: {{ $readiness['overall'] }}%"></div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <a href="{{ route('projects.show', $project->id) }}" class="px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                                Workspace
                            </a>
                            <a href="{{ route('projects.show', ['project' => $project->id, 'tab' => 'paper']) }}" class="px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-teal-600 hover:bg-teal-700 shadow-sm transition flex items-center space-x-1.5 active:scale-95">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>Tulis Paper</span>
                            </a>
                            <form method="POST" action="{{ route('projects.destroy', $project->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh proyek riset ini: {{ addslashes($project->title) }} beserta seluruh data dan naskahnya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 hover:border-rose-200 transition active:scale-95" title="Hapus Proyek Riset">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center bg-white rounded-2xl border border-slate-200/80">
                    <p class="text-sm font-semibold text-slate-600">Belum ada Research Project.</p>
                    <p class="text-xs text-slate-400 mt-1">Buat project baru atau promote ide dari Idea Repository.</p>
                </div>
            @endforelse
        </div>

        <div class="pt-4">
            {{ $projects->links() }}
        </div>

    </div>
</x-app-layout>
