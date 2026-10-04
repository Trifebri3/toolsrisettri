<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200/80">
            <div>
                <div class="flex items-center space-x-2 text-xs font-semibold text-teal-600">
                    <span class="tracking-wide uppercase">Pilar Utama &middot; Sains, Teknologi & Pengabdian Masyarakat</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">Pelacak Luaran & Publikasi Ilmiah</h1>
                <p class="text-xs text-slate-500">Monitoring seluruh naskah jurnal, prosiding konferensi, paten/HAKI, dan program pengabdian masyarakat (PKM).</p>
            </div>

            <!-- Stats Bar -->
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar">
                <div class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-xs shadow-2xs shrink-0">
                    <span class="text-slate-400">Total:</span>
                    <strong class="text-slate-800 ms-1">{{ $total }}</strong>
                </div>
                <div class="px-3 py-1.5 rounded-xl bg-sky-50 border border-sky-200/80 text-xs shadow-2xs shrink-0">
                    <span class="text-sky-700 font-semibold">Saintek:</span>
                    <strong class="text-sky-900 ms-1">{{ $totalSaintek }}</strong>
                </div>
                <div class="px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200/80 text-xs shadow-2xs shrink-0">
                    <span class="text-emerald-700 font-semibold">Pengabdian:</span>
                    <strong class="text-emerald-900 ms-1">{{ $totalPkm }}</strong>
                </div>
                <div class="px-3 py-1.5 rounded-xl bg-teal-50 border border-teal-200/80 text-xs shadow-2xs shrink-0">
                    <span class="text-teal-700 font-semibold">Published:</span>
                    <strong class="text-teal-900 ms-1">{{ $published }}</strong>
                </div>
            </div>
        </div>

        <!-- Primary Topic & Filter Bar -->
        <div class="space-y-2">
            <!-- Level 1: Core Topic Tabs (Sains & Teknologi vs Pengabdian Masyarakat) -->
            <div class="flex flex-wrap items-center gap-2 bg-slate-100/90 p-1.5 rounded-2xl border border-slate-200/80">
                <a href="{{ route('outputs.index') }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition active:scale-95 {{ !request('topic') && !request('output_type') ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                    Semua Bidang ({{ $total }})
                </a>
                <a href="{{ route('outputs.index', ['topic' => 'saintek']) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition active:scale-95 flex items-center space-x-1.5 {{ request('topic') === 'saintek' ? 'bg-sky-600 text-white shadow-sm' : 'text-slate-700 hover:bg-white/60' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    <span>Sains & Teknologi ({{ $totalSaintek }})</span>
                </a>
                <a href="{{ route('outputs.index', ['topic' => 'pkm']) }}"
                   class="px-4 py-2 rounded-xl text-xs font-bold transition active:scale-95 flex items-center space-x-1.5 {{ request('topic') === 'pkm' ? 'bg-emerald-600 text-white shadow-sm' : 'text-slate-700 hover:bg-white/60' }}">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Pengabdian Masyarakat / PKM ({{ $totalPkm }})</span>
                </a>
            </div>

            <!-- Level 2: Output Type Granular Chips -->
            <div class="flex flex-wrap items-center gap-1.5 bg-white p-2.5 rounded-xl border border-slate-200/80 shadow-2xs text-xs">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mr-1">Jenis Luaran:</span>
                <a href="{{ route('outputs.index', ['output_type' => 'journal_manuscript']) }}"
                   class="px-2.5 py-1 rounded-lg font-semibold {{ request('output_type') === 'journal_manuscript' ? 'bg-sky-100 text-sky-900 font-bold border border-sky-300' : 'text-slate-600 hover:bg-slate-100' }}">
                    Naskah Jurnal (Scopus/Sinta)
                </a>
                <a href="{{ route('outputs.index', ['output_type' => 'conference_paper']) }}"
                   class="px-2.5 py-1 rounded-lg font-semibold {{ request('output_type') === 'conference_paper' ? 'bg-sky-100 text-sky-900 font-bold border border-sky-300' : 'text-slate-600 hover:bg-slate-100' }}">
                    Paper Konferensi
                </a>
                <a href="{{ route('outputs.index', ['output_type' => 'community_service']) }}"
                   class="px-2.5 py-1 rounded-lg font-semibold {{ request('output_type') === 'community_service' ? 'bg-emerald-100 text-emerald-900 font-bold border border-emerald-300' : 'text-slate-600 hover:bg-slate-100' }}">
                    Pengabdian Masyarakat (PKM / Sinta)
                </a>
                <a href="{{ route('outputs.index', ['output_type' => 'dataset_repository']) }}"
                   class="px-2.5 py-1 rounded-lg font-semibold {{ request('output_type') === 'dataset_repository' ? 'bg-purple-100 text-purple-900 font-bold border border-purple-300' : 'text-slate-600 hover:bg-slate-100' }}">
                    Open Dataset & Telemetri
                </a>
                <a href="{{ route('outputs.index', ['output_type' => 'technical_report']) }}"
                   class="px-2.5 py-1 rounded-lg font-semibold {{ request('output_type') === 'technical_report' ? 'bg-slate-200 text-slate-900 font-bold border border-slate-300' : 'text-slate-600 hover:bg-slate-100' }}">
                    Laporan Teknis / Blueprint TTG
                </a>
            </div>
        </div>

        <!-- Outputs View: Mobile Responsive Cards (sm:hidden) & Desktop Table (hidden sm:block) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <!-- Mobile Cards List -->
            <div class="sm:hidden divide-y divide-slate-100">
                @forelse ($outputs as $out)
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
                        $isPkm = in_array($out->output_type, ['community_service', 'policy_brief']);
                    @endphp
                    <div class="p-4 space-y-2.5">
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $isPkm ? 'bg-emerald-100 text-emerald-900' : 'bg-sky-100 text-sky-900' }}">
                                {{ $isPkm ? 'PENGABDIAN (PKM)' : 'SAINS & TEKNOLOGI' }}
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusBadge }}">
                                {{ strtoupper($out->status) }}
                            </span>
                        </div>

                        <h3 class="font-bold text-slate-900 text-sm leading-snug">
                            {{ $out->title }}
                        </h3>

                        <div class="text-xs text-slate-500 space-y-1">
                            <p>Proyek: <a href="{{ route('projects.show', $out->research_project_id) }}" class="font-semibold text-teal-700 hover:underline">{{ $out->project->title ?? '-' }}</a></p>
                            @if ($out->target_venue)
                                <p>Venue: <span class="font-medium text-slate-800">{{ $out->target_venue }}</span> @if ($out->indexing) <span class="text-[10px] font-bold text-teal-700 bg-teal-50 px-1.5 py-0.2 rounded border border-teal-200">{{ $out->indexing }}</span> @endif</p>
                            @endif
                        </div>

                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between gap-2">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('projects.show', ['project' => $out->research_project_id, 'tab' => 'outputs']) }}" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-teal-800 bg-teal-50 border border-teal-200 hover:bg-teal-100 transition active:scale-95">
                                    Buka di Proyek →
                                </a>
                            </div>

                            <!-- Mobile Delete Button -->
                            <form method="POST" action="{{ route('outputs.destroy', $out->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus luaran ini: {{ addslashes($out->title) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-bold text-rose-600 hover:bg-rose-50 border border-rose-200 transition active:scale-95 flex items-center space-x-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    <span>Hapus</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400 text-xs">Belum ada target luaran terdaftar untuk filter ini.</div>
                @endforelse
            </div>

            <!-- Desktop Table View -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-700 font-bold border-b border-slate-200">
                            <th class="p-3.5">Judul Luaran & Bidang</th>
                            <th class="p-3.5">Proyek Riset Asal</th>
                            <th class="p-3.5">Target Venue & Indexing</th>
                            <th class="p-3.5">Status Publikasi</th>
                            <th class="p-3.5">DOI / Link</th>
                            <th class="p-3.5 text-right w-44">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($outputs as $out)
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
                                $isPkm = in_array($out->output_type, ['community_service', 'policy_brief']);
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition group">
                                <td class="p-3.5 align-top">
                                    <div class="flex items-center space-x-1.5 mb-1">
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $isPkm ? 'bg-emerald-100 text-emerald-900' : 'bg-sky-100 text-sky-900' }}">
                                            {{ $isPkm ? 'Pengabdian (PKM)' : 'Sains & Teknologi' }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-medium">
                                            &middot; {{ str_replace('_', ' ', strtoupper($out->output_type)) }}
                                        </span>
                                    </div>
                                    <h3 class="font-bold text-slate-900 text-sm leading-snug">{{ $out->title }}</h3>
                                    @if ($out->notes)
                                        <p class="text-[11px] text-slate-400 mt-1 italic">{{ $out->notes }}</p>
                                    @endif
                                </td>
                                <td class="p-3.5 align-top text-slate-600 font-medium">
                                    <a href="{{ route('projects.show', $out->research_project_id) }}" class="text-teal-700 hover:underline">
                                        {{ $out->project->title ?? '-' }}
                                    </a>
                                </td>
                                <td class="p-3.5 align-top">
                                    <div class="font-semibold text-slate-800">{{ $out->target_venue ?? 'Belum ditentukan' }}</div>
                                    @if ($out->indexing)
                                        <span class="inline-block text-[10px] font-bold text-teal-700 mt-0.5 bg-teal-50 px-1.5 py-0.2 rounded border border-teal-200">
                                            {{ $out->indexing }}
                                        </span>
                                    @endif
                                </td>
                                <td class="p-3.5 align-top">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ $statusBadge }}">
                                        {{ strtoupper($out->status) }}
                                    </span>
                                </td>
                                <td class="p-3.5 align-top">
                                    @if ($out->doi_or_url)
                                        <a href="{{ $out->doi_or_url }}" target="_blank" class="text-sky-600 hover:underline text-[11px] font-mono break-all">
                                            {{ Str::limit($out->doi_or_url, 28) }}
                                        </a>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="p-3.5 align-top text-right">
                                    <div class="flex items-center justify-end space-x-1.5">
                                        <a href="{{ route('projects.show', ['project' => $out->research_project_id, 'tab' => 'outputs']) }}" class="px-2.5 py-1 rounded-lg text-xs font-semibold text-teal-800 bg-teal-50 border border-teal-200 hover:bg-teal-100 transition shrink-0" title="Buka detail proyek">
                                            Buka →
                                        </a>

                                        <!-- Direct Delete Form -->
                                        <form method="POST" action="{{ route('outputs.destroy', $out->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus luaran ini: {{ addslashes($out->title) }}?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 py-1 rounded-lg text-xs font-semibold text-rose-600 hover:text-white hover:bg-rose-600 border border-rose-200 transition active:scale-95 flex items-center space-x-1 shrink-0" title="Hapus Luaran">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-slate-400">Belum ada target luaran terdaftar untuk filter ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="pt-4">
            {{ $outputs->links() }}
        </div>

    </div>
</x-app-layout>
