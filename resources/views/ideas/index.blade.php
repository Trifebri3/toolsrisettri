<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200/80">
            <div>
                <div class="flex items-center space-x-2 text-xs font-semibold text-sky-600">
                    <span class="tracking-wide uppercase">Modul 1 • Idea Repository</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">Repositori Ide Penelitian</h1>
                <p class="text-xs text-slate-500">Tangkap ide penelitian dalam detik. AI secara otomatis mengelompokkan topik, potensi luaran, dan prioritas.</p>
            </div>
            <div>
                <a href="#quick-capture-form" class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-sky-600 to-teal-600 hover:opacity-95 shadow-sm transition">
                    + Tangkap Ide Baru (< 1 Menit)
                </a>
            </div>
        </div>

        <!-- Quick Capture Drawer / Card with AI Live Preview -->
        <div id="quick-capture-form" class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-2xs space-y-4"
             x-data="{
                title: '',
                description: '',
                field: '',
                priority: 'medium',
                aiResult: null,
                isAnalyzing: false,
                classify() {
                    if (this.title.length < 5) return;
                    this.isAnalyzing = true;
                    fetch('{{ route('ideas.classify') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ title: this.title, description: this.description })
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.aiResult = data;
                        if (!this.field) this.field = data.field;
                        this.priority = data.priority;
                        this.isAnalyzing = false;
                    })
                    .catch(() => this.isAnalyzing = false);
                }
             }">

            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center space-x-2.5">
                    <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Form Cepat Penangkapan Ide</h2>
                        <p class="text-[11px] text-slate-400">Input minimalis tanpa formulir rumit. Ketikkan judul ide untuk melihat klasifikasi otomatis AI.</p>
                    </div>
                </div>
                <div x-show="isAnalyzing" class="text-xs text-teal-600 flex items-center space-x-1.5" x-cloak>
                    <svg class="animate-spin h-3.5 w-3.5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                    <span>AI Menganalisis...</span>
                </div>
            </div>

            <form method="POST" action="{{ route('ideas.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-8 space-y-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Judul / Inti Ide Riset *</label>
                            <input type="text" name="title" x-model="title" @input.debounce.500ms="classify()" required
                                   placeholder="Contoh: Sistem Akustofluidik Pemisahan Mikroplastik di Muara Sungai..."
                                   class="w-full text-sm rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Singkat / Catatan Lapangan (Opsional)</label>
                            <textarea name="description" x-model="description" @input.debounce.800ms="classify()" rows="3"
                                      placeholder="Tuliskan latar belakang singkat, inspirasi masalah, atau catatan teknologi yang ingin dicoba..."
                                      class="w-full text-xs rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 transition"></textarea>
                        </div>
                    </div>

                    <div class="md:col-span-4 space-y-3 bg-slate-50/70 p-4 rounded-xl border border-slate-100">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Bidang Kajian</label>
                            <input type="text" name="field" x-model="field" placeholder="Terdeteksi otomatis..."
                                   class="w-full text-xs rounded-lg border-slate-200 focus:border-teal-500 focus:ring-teal-500 bg-white">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Tingkat Prioritas</label>
                            <select name="priority" x-model="priority" class="w-full text-xs rounded-lg border-slate-200 focus:border-teal-500 focus:ring-teal-500 bg-white">
                                <option value="high">Tinggi (High Priority)</option>
                                <option value="medium">Sedang (Medium Priority)</option>
                                <option value="low">Rendah (Backlog)</option>
                            </select>
                        </div>

                        <!-- AI Suggestion Box Preview -->
                        <template x-if="aiResult">
                            <div class="mt-2 p-3 bg-white rounded-lg border border-teal-200 text-[11px] space-y-1.5 animate-fade-in">
                                <div class="font-bold text-teal-800 flex items-center space-x-1">
                                    <span>Prediksi AI:</span>
                                    <span class="text-slate-600 font-normal" x-text="aiResult.research_type"></span>
                                </div>
                                <div class="text-slate-600">
                                    <span class="font-semibold text-slate-800">Potensi Luaran:</span>
                                    <template x-for="out in aiResult.potential_outputs" :key="out">
                                        <span class="inline-block px-1.5 py-0.5 bg-slate-100 text-slate-700 rounded text-[9px] me-1 mb-1" x-text="out"></span>
                                    </template>
                                </div>
                                <div class="text-slate-500 text-[10px] italic" x-text="aiResult.analysis.novelty_angle"></div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-2">
                    <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-xs text-white bg-gradient-to-r from-sky-600 to-teal-600 hover:opacity-95 shadow-sm transition">
                        Simpan Ide Penelitian (< 1 Menit)
                    </button>
                </div>
            </form>
        </div>

        <!-- Filter & Search Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-white p-3 rounded-xl border border-slate-200/80 shadow-2xs">
            <div class="flex items-center space-x-2 w-full sm:w-auto">
                <a href="{{ route('ideas.index') }}" class="px-3 py-1 rounded-lg text-xs font-semibold {{ !request('status') ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'text-slate-600 hover:bg-slate-50' }}">
                    Semua Ide
                </a>
                <a href="{{ route('ideas.index', ['status' => 'captured']) }}" class="px-3 py-1 rounded-lg text-xs font-semibold {{ request('status') === 'captured' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'text-slate-600 hover:bg-slate-50' }}">
                    Captured (Belum Di-promote)
                </a>
                <a href="{{ route('ideas.index', ['status' => 'promoted']) }}" class="px-3 py-1 rounded-lg text-xs font-semibold {{ request('status') === 'promoted' ? 'bg-teal-50 text-teal-700 border border-teal-200' : 'text-slate-600 hover:bg-slate-50' }}">
                    Promoted to Project
                </a>
            </div>
            <div class="text-xs text-slate-500">
                Menampilkan <strong>{{ $ideas->total() }}</strong> ide penelitian
            </div>
        </div>

        <!-- Ideas Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse ($ideas as $idea)
                @php
                    $isPromoted = $idea->status === 'promoted';
                    $priorityColor = match($idea->priority) {
                        'high' => 'bg-rose-50 text-rose-700 border-rose-200',
                        'medium' => 'bg-amber-50 text-amber-700 border-amber-200',
                        default => 'bg-slate-100 text-slate-600 border-slate-200',
                    };
                @endphp
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs hover:border-teal-300 transition flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border {{ $priorityColor }}">
                                {{ $idea->priority }}
                            </span>

                            @if ($isPromoted)
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-teal-50 text-teal-800 border border-teal-200">
                                    Promoted
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-sky-50 text-sky-800 border border-sky-200">
                                    Raw Idea
                                </span>
                            @endif
                        </div>

                        <div>
                            <span class="text-[11px] font-semibold text-slate-400">{{ $idea->field }}</span>
                            <h3 class="text-base font-bold text-slate-900 mt-0.5 leading-snug">
                                {{ $idea->title }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-2 line-clamp-3 leading-relaxed">
                                {{ $idea->description ?? 'Tidak ada catatan tambahan.' }}
                            </p>
                        </div>

                        <!-- AI Suggestions Chips -->
                        @if (!empty($idea->potential_outputs))
                            <div class="pt-2 border-t border-slate-100">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Potensi Luaran:</span>
                                <div class="flex flex-wrap gap-1">
                                    @foreach ($idea->potential_outputs as $out)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-medium bg-slate-50 text-slate-700 border border-slate-200/70">
                                            {{ $out }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if (!empty($idea->ai_analysis['novelty_angle']))
                            <div class="p-2.5 rounded-xl bg-teal-50/60 border border-teal-100 text-[11px] text-teal-900">
                                <span class="font-bold text-teal-800">Sudut Kebaruan (Novelty):</span>
                                <p class="text-[10px] text-slate-600 mt-0.5">{{ $idea->ai_analysis['novelty_angle'] }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Card Actions -->
                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                        <form method="POST" action="{{ route('ideas.destroy', $idea->id) }}" onsubmit="return confirm('Hapus ide ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-slate-400 hover:text-rose-600 transition">
                                Hapus
                            </button>
                        </form>

                        @if ($isPromoted && $idea->research_project_id)
                            <a href="{{ route('projects.show', $idea->research_project_id) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-teal-800 bg-teal-50 border border-teal-200 hover:bg-teal-100 transition">
                                Buka Workspace Project →
                            </a>
                        @else
                            <form method="POST" action="{{ route('ideas.promote', $idea->id) }}">
                                @csrf
                                <button type="submit" class="px-3.5 py-1.5 rounded-lg text-xs font-bold text-white bg-gradient-to-r from-sky-600 to-teal-600 hover:opacity-95 shadow-2xs transition flex items-center space-x-1">
                                    <span>Promote to Project</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-12 text-center bg-white rounded-2xl border border-slate-200/80">
                    <p class="text-sm font-semibold text-slate-600">Belum ada ide yang tersimpan.</p>
                    <p class="text-xs text-slate-400 mt-1">Gunakan form di atas untuk menangkap ide penelitian pertama Anda.</p>
                </div>
            @endforelse
        </div>

        <div class="pt-4">
            {{ $ideas->links() }}
        </div>

    </div>
</x-app-layout>
