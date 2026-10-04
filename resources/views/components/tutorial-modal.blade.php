<div x-show="tutorialModal"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-3 sm:p-6"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">

    <div class="bg-white rounded-3xl max-w-2xl w-full shadow-2xl border border-slate-200 overflow-hidden animate-scale-in"
         x-data="{
             step: 1,
             totalSteps: 5,
             setStep(s) { this.step = s; },
             nextStep() { if (this.step < this.totalSteps) this.step++; },
             prevStep() { if (this.step > 1) this.step--; }
         }"
         @click.away="tutorialModal = false">

        <!-- Modal Top Bar -->
        <div class="px-6 py-4 bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-600 text-white flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-white/20 backdrop-blur-xs flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-black tracking-tight">Panduan Alur Riset Research OS</h2>
                    <p class="text-[11px] text-teal-100">5 Langkah Mudah Mengubah Ide Menjadi Paper Ilmiah Siap Publikasi</p>
                </div>
            </div>

            <button type="button" @click="tutorialModal = false" class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition active:scale-90">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Stepper Navigation Pills -->
        <div class="px-6 pt-4 pb-3 bg-slate-50 border-b border-slate-200/80 flex items-center justify-between gap-1 overflow-x-auto no-scrollbar">
            <button type="button" @click="setStep(1)"
                    :class="step === 1 ? 'bg-teal-600 text-white font-bold shadow-xs' : (step > 1 ? 'bg-teal-100 text-teal-800' : 'bg-white text-slate-600 border border-slate-200')"
                    class="px-3 py-1.5 rounded-xl text-xs transition shrink-0 flex items-center space-x-1.5 active:scale-95">
                <span class="w-4 h-4 rounded-full flex items-center justify-center text-[10px] font-bold" :class="step === 1 ? 'bg-white text-teal-700' : (step > 1 ? 'bg-teal-700 text-white' : 'bg-slate-200 text-slate-700')">1</span>
                <span>Ide Riset</span>
            </button>

            <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>

            <button type="button" @click="setStep(2)"
                    :class="step === 2 ? 'bg-teal-600 text-white font-bold shadow-xs' : (step > 2 ? 'bg-teal-100 text-teal-800' : 'bg-white text-slate-600 border border-slate-200')"
                    class="px-3 py-1.5 rounded-xl text-xs transition shrink-0 flex items-center space-x-1.5 active:scale-95">
                <span class="w-4 h-4 rounded-full flex items-center justify-center text-[10px] font-bold" :class="step === 2 ? 'bg-white text-teal-700' : (step > 2 ? 'bg-teal-700 text-white' : 'bg-slate-200 text-slate-700')">2</span>
                <span>Pondasi & RQ</span>
            </button>

            <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>

            <button type="button" @click="setStep(3)"
                    :class="step === 3 ? 'bg-teal-600 text-white font-bold shadow-xs' : (step > 3 ? 'bg-teal-100 text-teal-800' : 'bg-white text-slate-600 border border-slate-200')"
                    class="px-3 py-1.5 rounded-xl text-xs transition shrink-0 flex items-center space-x-1.5 active:scale-95">
                <span class="w-4 h-4 rounded-full flex items-center justify-center text-[10px] font-bold" :class="step === 3 ? 'bg-white text-teal-700' : (step > 3 ? 'bg-teal-700 text-white' : 'bg-slate-200 text-slate-700')">3</span>
                <span>Data & Dokumen</span>
            </button>

            <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>

            <button type="button" @click="setStep(4)"
                    :class="step === 4 ? 'bg-teal-600 text-white font-bold shadow-xs' : (step > 4 ? 'bg-teal-100 text-teal-800' : 'bg-white text-slate-600 border border-slate-200')"
                    class="px-3 py-1.5 rounded-xl text-xs transition shrink-0 flex items-center space-x-1.5 active:scale-95">
                <span class="w-4 h-4 rounded-full flex items-center justify-center text-[10px] font-bold" :class="step === 4 ? 'bg-white text-teal-700' : (step > 4 ? 'bg-teal-700 text-white' : 'bg-slate-200 text-slate-700')">4</span>
                <span>Klaim & Bukti</span>
            </button>

            <svg class="w-3.5 h-3.5 text-slate-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>

            <button type="button" @click="setStep(5)"
                    :class="step === 5 ? 'bg-teal-600 text-white font-bold shadow-xs' : 'bg-white text-slate-600 border border-slate-200'"
                    class="px-3 py-1.5 rounded-xl text-xs transition shrink-0 flex items-center space-x-1.5 active:scale-95">
                <span class="w-4 h-4 rounded-full flex items-center justify-center text-[10px] font-bold" :class="step === 5 ? 'bg-white text-teal-700' : 'bg-slate-200 text-slate-700'">5</span>
                <span>Paper Builder</span>
            </button>
        </div>

        <!-- Step Content Body -->
        <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto">

            <!-- STEP 1: IDEA VAULT -->
            <div x-show="step === 1" class="space-y-4 animate-fade-in">
                <div class="flex items-start space-x-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-black shrink-0 border border-teal-200">
                        1
                    </div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-teal-700">Langkah 1 &middot; Modul Ide</span>
                        <h3 class="text-base font-extrabold text-slate-900 mt-0.5">Tangkap Ide & Klasifikasikan Otomatis</h3>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            Jangan biarkan ide riset hilang atau berserakan di catatan acak. Catat ide riset Anda di <strong>Idea Repository</strong> bahkan saat sedang di lapangan atau offline.
                        </p>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/80 space-y-2.5 text-xs text-slate-700">
                    <div class="font-bold text-slate-900">Apa yang dilakukan Research OS?</div>
                    <ul class="space-y-1.5 list-disc list-inside text-slate-600">
                        <li>AI secara otomatis mengidentifikasi <strong>rumpun ilmu</strong> (misal: Smart Agriculture, IoT, AI Vision, Energi Terbarukan).</li>
                        <li>Menentukan estimasi <strong>potensi luaran</strong> (Jurnal Scopus/Sinta, Prosiding Konferensi, Dataset Terbuka, atau PKM).</li>
                        <li>Memberikan rekomendasi <strong>celah riset awal (gap)</strong> dan metodologi yang cocok.</li>
                    </ul>
                </div>

                <div class="p-3 bg-teal-50/70 rounded-xl border border-teal-200 text-xs text-teal-900 flex items-center justify-between">
                    <span>Setelah ide matang, klik <strong>Promote ke Project</strong> untuk membuat paper baru.</span>
                    <a href="{{ route('ideas.index') }}" class="px-3 py-1.5 bg-teal-600 text-white rounded-lg font-bold hover:bg-teal-700 transition shrink-0 ml-2">
                        Buka Idea Vault &rarr;
                    </a>
                </div>
            </div>

            <!-- STEP 2: FOUNDATION & RQ -->
            <div x-show="step === 2" class="space-y-4 animate-fade-in">
                <div class="flex items-start space-x-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-sky-50 text-sky-700 flex items-center justify-center font-black shrink-0 border border-sky-200">
                        2
                    </div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-sky-700">Langkah 2 &middot; Pondasi Riset</span>
                        <h3 class="text-base font-extrabold text-slate-900 mt-0.5">Rumuskan Problem, Gap & Research Question</h3>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            Paper yang kuat berawal dari pondasi yang kokoh. Di tahap ini, Anda menentukan fokus ilmiah yang membedakan penelitian Anda dari karya sebelumnya.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                        <strong class="text-slate-900 block font-bold">Research Gap</strong>
                        <p class="text-slate-600 text-[11px] leading-relaxed">Jelaskan apa kekurangan atau keterbatasan solusi yang ada saat ini yang belum terpecahkan.</p>
                    </div>
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                        <strong class="text-slate-900 block font-bold">Novelty & Kontribusi</strong>
                        <p class="text-slate-600 text-[11px] leading-relaxed">Tegaskan kebaruan karya Anda (apakah algoritma baru, formulasi kalibrasi, atau arsitektur sistem).</p>
                    </div>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 space-y-1.5">
                    <div class="font-bold text-slate-900">Research Questions (RQ) Tracker:</div>
                    <p class="text-slate-600 text-[11px]">Tambahkan minimal 1-3 pertanyaan penelitian terukur. Setiap RQ akan dikaitkan dengan metode dan temuan empiris di langkah berikutnya.</p>
                </div>
            </div>

            <!-- STEP 3: DATA & DOCUMENTS -->
            <div x-show="step === 3" class="space-y-4 animate-fade-in">
                <div class="flex items-start space-x-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center font-black shrink-0 border border-teal-200">
                        3
                    </div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-teal-700">Langkah 3 &middot; Manajemen Data</span>
                        <h3 class="text-base font-extrabold text-slate-900 mt-0.5">Kumpulkan Data, Dokumen & Bukti Lapangan</h3>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            Simpan seluruh aset penelitian di tab <strong>Data & Dokumen</strong> sehingga tidak ada berkas yang hilang saat naskah mulai ditulis.
                        </p>
                    </div>
                </div>

                <div class="space-y-2 text-xs">
                    <div class="p-3 rounded-xl bg-white border border-slate-200 flex items-center space-x-3">
                        <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-xs shrink-0">A</div>
                        <div>
                            <span class="font-bold text-slate-900">Upload Dokumen Pendukung:</span>
                            <span class="text-slate-500 block text-[11px]">Proposal, ethical clearance, instrumen kuesioner, spreadsheet CSV, atau foto dokumentasi.</span>
                        </div>
                    </div>
                    <div class="p-3 rounded-xl bg-white border border-slate-200 flex items-center space-x-3">
                        <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-xs shrink-0">B</div>
                        <div>
                            <span class="font-bold text-slate-900">Catat Dataset & Evidence:</span>
                            <span class="text-slate-500 block text-[11px]">Daftarkan dataset uji (sensor log, uji lab) beserta poin metrik kunci (misal: R² = 0.984, efisiensi daya 32%).</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 4: ANALYSIS & CLAIMS (ANTI-HALLUCINATION) -->
            <div x-show="step === 4" class="space-y-4 animate-fade-in">
                <div class="flex items-start space-x-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-700 flex items-center justify-center font-black shrink-0 border border-rose-200">
                        4
                    </div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-rose-700">Langkah 4 &middot; Integritas Ilmiah</span>
                        <h3 class="text-base font-extrabold text-slate-900 mt-0.5">Analisis & Hubungkan Bukti ke Klaim (Traceability)</h3>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            <strong>Inilah keunggulan utama Research OS:</strong> Memastikan setiap klaim ilmiah Anda memiliki bukti nyata sebelum masuk ke draft naskah.
                        </p>
                    </div>
                </div>

                <div class="bg-rose-50/70 border border-rose-200 rounded-2xl p-4 space-y-2 text-xs text-rose-900">
                    <div class="font-bold flex items-center space-x-1.5">
                        <svg class="w-4 h-4 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>Aturan Anti-Halusinasi Research OS:</span>
                    </div>
                    <p class="text-[11px] text-rose-800 leading-relaxed">
                        Jika Anda membuat pernyataan ilmiah tanpa menghubungkannya ke bukti empiris, sistem memberi status <strong>[Missing Evidence]</strong> dan tidak akan memasukkannya ke draf akhir. Setelah bukti dihubungkan, status berubah menjadi <strong>[Grounded in Evidence]</strong>.
                    </p>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs text-slate-600">
                    Gunakan <strong>AI Peer Reviewer Mode</strong> di tab AI Assistant untuk menguji kelemahan argumen klaim Anda sebelum di-submit ke reviewer jurnal.
                </div>
            </div>

            <!-- STEP 5: PAPER BUILDER & EXPORT -->
            <div x-show="step === 5" class="space-y-4 animate-fade-in">
                <div class="flex items-start space-x-3.5">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-black shrink-0 border border-emerald-200">
                        5
                    </div>
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Langkah 5 &middot; Penulisan & Publikasi</span>
                        <h3 class="text-base font-extrabold text-slate-900 mt-0.5">Susun Paper IMRaD & Ekspor Naskah</h3>
                        <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                            Di tab <strong>Paper Builder</strong>, Anda menyusun naskah terstruktur lengkap dengan standar IMRaD (Title, Abstract, Introduction, Methodology, Results, Discussion, Conclusion).
                        </p>
                    </div>
                </div>

                <div class="space-y-2 text-xs">
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                        <strong class="text-slate-900 font-bold block">Draf AI Berbasis Bukti</strong>
                        <p class="text-slate-600 text-[11px] leading-relaxed">
                            Klik tombol <em>Draf AI Berbasis Bukti</em> di setiap seksi. AI akan menyintesis kalimat akademik berdasarkan data dan klaim yang sudah <strong>Grounded</strong> tanpa mengarang data baru.
                        </p>
                    </div>

                    <div class="p-3.5 rounded-xl bg-emerald-50/70 border border-emerald-200 text-emerald-950 space-y-1">
                        <strong class="text-emerald-900 font-bold block">Ekspor Naskah & Multi-Output</strong>
                        <p class="text-emerald-800 text-[11px] leading-relaxed">
                            Klik tombol <strong>Export .MD</strong> di pojok kanan atas untuk mengunduh seluruh draf paper dalam format Markdown (.md) yang siap di-copy ke Word, Overleaf, LaTeX, atau Google Docs.
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Modal Bottom Bar: Navigation & Action -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200/80 flex items-center justify-between">
            <button type="button" @click="prevStep()" :disabled="step === 1"
                    :class="step === 1 ? 'opacity-30 pointer-events-none' : 'text-slate-700 hover:text-slate-900'"
                    class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-xs font-bold transition active:scale-95 flex items-center space-x-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Sebelumnya</span>
            </button>

            <span class="text-xs font-semibold text-slate-400">
                Tahap <strong class="text-teal-700" x-text="step"></strong> dari 5
            </span>

            <template x-if="step < totalSteps">
                <button type="button" @click="nextStep()"
                        class="px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold transition active:scale-95 shadow-sm flex items-center space-x-1">
                    <span>Lanjut Langkah Berikutnya</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </template>

            <template x-if="step === totalSteps">
                <button type="button" @click="tutorialModal = false"
                        class="px-5 py-2 rounded-xl bg-gradient-to-r from-teal-600 to-emerald-600 hover:opacity-95 text-white text-xs font-bold transition active:scale-95 shadow-sm flex items-center space-x-1">
                    <span>Selesai & Mulai Menulis</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </button>
            </template>
        </div>

    </div>
</div>
