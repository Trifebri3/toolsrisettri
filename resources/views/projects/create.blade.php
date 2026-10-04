<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <div class="flex items-center justify-between pb-2 border-b border-slate-200/80">
            <div>
                <a href="{{ route('projects.index') }}" class="text-xs font-semibold text-slate-400 hover:text-slate-600 transition flex items-center space-x-1 mb-1">
                    <span>← Kembali ke Daftar Proyek</span>
                </a>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Buat Research Project Baru</h1>
                <p class="text-xs text-slate-500">Mulai inisialisasi ruang kerja penelitian baru dengan pondasi terstruktur.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-2xs">
            <form method="POST" action="{{ route('projects.store') }}" class="space-y-5">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Judul Penelitian (Title) *</label>
                        <input type="text" name="title" required
                               placeholder="Contoh: Low-Power Edge-IoT Sensor Mesh for Precision Soil Monitoring in Subak Agriculture"
                               class="w-full text-sm rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 transition">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div x-data="{ selectedField: 'Sains & Teknologi' }">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Bidang Penelitian / Topik Utama *</label>
                            <input type="text" name="field" x-model="selectedField" required
                                   placeholder="Contoh: Sains & Teknologi, Pengabdian Masyarakat (PKM)"
                                   class="w-full text-xs rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 transition">
                            <div class="flex flex-wrap items-center gap-1.5 mt-2">
                                <span class="text-[10px] text-slate-400 font-semibold">Pilih:</span>
                                <button type="button" @click="selectedField = 'Sains & Teknologi'" class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-sky-50 text-sky-800 border border-sky-200 hover:bg-sky-100 transition active:scale-95">
                                    Sains & Teknologi
                                </button>
                                <button type="button" @click="selectedField = 'Pengabdian Masyarakat (PKM)'" class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100 transition active:scale-95">
                                    Pengabdian Masyarakat (PKM)
                                </button>
                                <button type="button" @click="selectedField = 'Teknologi Tepat Guna (TTG)'" class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-teal-50 text-teal-800 border border-teal-200 hover:bg-teal-100 transition active:scale-95">
                                    Teknologi Tepat Guna
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Target Deadline Naskah</label>
                            <input type="date" name="target_deadline"
                                   class="w-full text-xs rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Ringkasan / Executive Summary</label>
                        <textarea name="summary" rows="3"
                                  placeholder="Jelaskan secara garis besar tujuan utama penelitian dan apa yang ingin dicapai..."
                                  class="w-full text-xs rounded-xl border-slate-200 focus:border-teal-500 focus:ring-teal-500 transition"></textarea>
                    </div>

                    <div class="p-4 bg-slate-50/80 rounded-xl border border-slate-100 space-y-4">
                        <div class="flex items-center space-x-2 text-xs font-bold text-slate-800">
                            <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                            <span>Research Foundation (Bisa dilengkapi nanti di Workspace):</span>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Problem Statement</label>
                            <textarea name="problem_statement" rows="2"
                                      placeholder="Masalah empiris atau teoretis yang dihadapi di lapangan..."
                                      class="w-full text-xs rounded-lg border-slate-200 focus:border-teal-500 focus:ring-teal-500 bg-white"></textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Research Gap</label>
                            <textarea name="research_gap" rows="2"
                                      placeholder="Apa yang belum dipecahkan oleh penelitian-penelitian sebelumnya?"
                                      class="w-full text-xs rounded-lg border-slate-200 focus:border-teal-500 focus:ring-teal-500 bg-white"></textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Novelty & Kontribusi Ilmiah</label>
                            <textarea name="novelty" rows="2"
                                      placeholder="Sudut kebaruan metodologi, algoritma, atau efisiensi sistem..."
                                      class="w-full text-xs rounded-lg border-slate-200 focus:border-teal-500 focus:ring-teal-500 bg-white"></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
                    <a href="{{ route('projects.index') }}" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-xs text-white bg-gradient-to-r from-sky-600 to-teal-600 hover:opacity-95 shadow-sm transition">
                        Buat & Buka Workspace Proyek
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-layout>
