<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6" x-data="{ addLitModal: false, showMatrix: window.innerWidth > 768 }">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200/80">
            <div>
                <div class="flex items-center space-x-2 text-xs font-semibold text-sky-600">
                    <span class="tracking-wide uppercase">Modul 3 • Literature Repository</span>
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">Repositori Literatur & Matriks Sintesis</h1>
                <p class="text-xs text-slate-500">Kelola referensi jurnal, temuan kunci, metodologi studi terdahulu, dan matriks pembanding novelty.</p>
            </div>
            <div class="flex items-center space-x-2">
                <button @click="showMatrix = !showMatrix" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition shadow-2xs active:scale-95">
                    <span x-text="showMatrix ? 'Lihat Mode Kartu' : 'Tampilkan Matriks Sintesis'"></span>
                </button>
                <button @click="addLitModal = true" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-sky-600 to-teal-600 hover:opacity-95 shadow-sm transition active:scale-95">
                    + Tambah Referensi
                </button>
            </div>
        </div>

        <!-- 1. Interactive Literature Matrix View (Side-by-Side Comparison) -->
        <div x-show="showMatrix" class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs space-y-3 animate-fade-in" x-cloak>
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-teal-50 text-teal-800 border border-teal-200">
                        Matriks Sintesis Literatur (Literature Matrix)
                    </span>
                    <span class="text-xs text-slate-400">• Analisis Kesenjangan & Diferensiasi</span>
                </div>
                <span class="text-xs font-semibold text-teal-700 bg-teal-50 px-2.5 py-0.5 rounded-md">
                    {{ count($matrixItems) }} Paper Terpetakan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-700 font-bold border-b border-slate-200">
                            <th class="p-3 w-48">Penulis & Tahun</th>
                            <th class="p-3 w-56">Venue / Jurnal</th>
                            <th class="p-3 w-64">Metode yang Digunakan</th>
                            <th class="p-3 w-64">Keterbatasan / Gap</th>
                            <th class="p-3 w-72 text-teal-800 bg-teal-50/50">Diferensiasi / Novelty Kami</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($matrixItems as $m)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="p-3 font-bold text-slate-900 align-top">
                                    {{ $m->authors ?? 'Anonim' }} ({{ $m->year ?? '-' }})
                                    @if ($m->doi)
                                        <a href="{{ $m->url ?? 'https://doi.org/' . $m->doi }}" target="_blank" class="block text-[10px] text-sky-600 font-normal underline mt-0.5">DOI</a>
                                    @endif
                                </td>
                                <td class="p-3 text-slate-600 align-top leading-relaxed">
                                    {{ $m->venue ?? '-' }}
                                </td>
                                <td class="p-3 text-slate-700 align-top leading-relaxed">
                                    {{ $m->methodology_used ?? 'Standar eksperimental' }}
                                </td>
                                <td class="p-3 text-rose-800/90 bg-rose-50/30 align-top leading-relaxed font-medium">
                                    {{ $m->limitations ?? 'Perlu studi lanjutan pada kondisi lapangan dinamis.' }}
                                </td>
                                <td class="p-3 text-teal-900 bg-teal-50/40 align-top font-semibold leading-relaxed">
                                    {{ $m->our_differentiation ?? 'Pendekatan terintegrasi kami.' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-slate-400">Belum ada literatur dalam matriks.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Literature Cards List View -->
        <div x-show="!showMatrix" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 animate-fade-in" x-cloak>
            @forelse ($literatures as $lit)
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-2xs hover:border-teal-300 transition flex flex-col justify-between space-y-3">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 bg-slate-100 rounded text-slate-700">
                                {{ $lit->year ?? 'N/A' }}
                            </span>
                            @if ($lit->citation_key)
                                <span class="font-mono text-[10px] text-teal-700 bg-teal-50 px-2 py-0.5 rounded border border-teal-100">
                                    \{{ $lit->citation_key }}
                                </span>
                            @endif
                        </div>

                        <h3 class="text-sm font-bold text-slate-900 leading-snug">
                            {{ $lit->title }}
                        </h3>

                        <p class="text-xs text-slate-600 font-medium">
                            {{ $lit->authors }}
                        </p>

                        <p class="text-[11px] text-slate-400 italic">
                            {{ $lit->venue }}
                        </p>

                        @if ($lit->key_findings)
                            <div class="pt-2 border-t border-slate-100 text-xs text-slate-600">
                                <strong class="text-slate-800 text-[11px] block">Temuan Kunci:</strong>
                                <p class="line-clamp-3 text-[11px] mt-0.5 leading-relaxed">{{ $lit->key_findings }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        @if ($lit->doi)
                            <a href="https://doi.org/{{ $lit->doi }}" target="_blank" class="text-sky-600 hover:underline text-[11px]">
                                Buka Paper (DOI) →
                            </a>
                        @else
                            <span></span>
                        @endif

                        <form method="POST" action="{{ route('literature.destroy', $lit->id) }}" onsubmit="return confirm('Hapus referensi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-slate-400 hover:text-rose-600 text-xs">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-12 text-center bg-white rounded-2xl border border-slate-200">
                    <p class="text-sm font-semibold text-slate-600">Belum ada literatur yang disimpan.</p>
                </div>
            @endforelse
        </div>

        <div class="pt-4">
            {{ $literatures->links() }}
        </div>

        <!-- Modal Tambah Literatur -->
        <div x-show="addLitModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4" x-cloak>
            <div class="bg-white rounded-2xl max-w-2xl w-full p-6 space-y-4 shadow-xl border border-slate-200 animate-scale-in">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Tambah Referensi Literatur</h3>
                    <button @click="addLitModal = false" class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('literature.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul Paper / Buku *</label>
                        <input type="text" name="title" required placeholder="Contoh: Energy-Efficient LoRa-Based Sensor Networks..." class="w-full text-xs rounded-xl border-slate-200">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Penulis (Authors)</label>
                            <input type="text" name="authors" placeholder="Zhao, M., Sanchez, F., et al." class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Tahun Publikasi</label>
                            <input type="number" name="year" value="2025" class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Venue / Nama Jurnal / Konferensi</label>
                            <input type="text" name="venue" placeholder="IEEE Internet of Things Journal" class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Hubungkan ke Project</label>
                            <select name="research_project_id" class="w-full text-xs rounded-xl border-slate-200">
                                <option value="">-- Pustaka Umum --</option>
                                @foreach ($projects as $p)
                                    <option value="{{ $p->id }}">{{ Str::limit($p->title, 40) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Metodologi Studi Terdahulu</label>
                            <input type="text" name="methodology_used" placeholder="Star topology LoRaWAN gateway..." class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Keterbatasan / Gap Paper Ini</label>
                            <input type="text" name="limitations" placeholder="Hanya diuji pada lahan datar..." class="w-full text-xs rounded-xl border-slate-200">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Diferensiasi / Kebaruan Pendekatan Kita (Novelty)</label>
                        <input type="text" name="our_differentiation" placeholder="Kita menggunakan multi-hop mesh untuk terasering bertingkat..." class="w-full text-xs rounded-xl border-slate-200">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Temuan Kunci (Key Findings)</label>
                        <textarea name="key_findings" rows="2" class="w-full text-xs rounded-xl border-slate-200"></textarea>
                    </div>

                    <div class="flex justify-end space-x-2 pt-2">
                        <button type="button" @click="addLitModal = false" class="px-3 py-1.5 text-xs text-slate-500">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold">Simpan ke Repositori</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>
