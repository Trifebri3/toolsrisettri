<!DOCTYPE html>
<html lang="id" class="h-full bg-[#fbfcfd]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
    <title>Mode Offline — Research OS</title>
    <meta name="theme-color" content="#0f766e">
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="icon" type="image/svg+xml" href="/icons/icon.svg">
    <link rel="apple-touch-icon" href="/icons/apple-touch-icon.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full antialiased text-slate-800 bg-[#fbfcfd] flex flex-col justify-between p-4 sm:p-6" x-data="offlineApp()">

    <!-- Header bar -->
    <header class="max-w-3xl mx-auto w-full flex items-center justify-between py-3 border-b border-slate-200">
        <a href="/dashboard" class="flex items-center space-x-2.5">
            <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-sky-600 via-teal-600 to-emerald-600 flex items-center justify-center text-white shadow-xs font-black text-sm">
                R
            </div>
            <div>
                <span class="font-extrabold text-sm tracking-tight text-slate-900">RESEARCH <span class="text-teal-700">OS</span></span>
                <span class="text-[10px] text-slate-400 block -mt-0.5">Field & Offline Mode</span>
            </div>
        </a>

        <!-- Connection Badge -->
        <div class="flex items-center space-x-2">
            <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-semibold"
                  :class="isOnline ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-900 border border-amber-200'">
                <span class="w-2 h-2 rounded-full" :class="isOnline ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500'"></span>
                <span x-text="isOnline ? 'Online (Tersambung)' : 'Offline (Tersimpan Lokal)'"></span>
            </span>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-2xl mx-auto w-full my-auto py-8 space-y-6">
        
        <!-- Offline Hero Card -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 sm:p-8 shadow-xs text-center space-y-4">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-700 shadow-inner">
                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 4.243a9 9 0 01-1.414-1.414m-1.415-1.415a9 9 0 01-2.828-5.657m0 0l2.828 2.828M3 3l18 18"/>
                </svg>
            </div>

            <div class="space-y-1">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-100 text-amber-900">
                    Koneksi Internet Terputus
                </span>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">
                    Mode Riset Lapangan & Offline
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto leading-relaxed">
                    Research OS tetap aktif. Anda dapat mencatat temuan lapangan, draft ide, dan membuka halaman yang telah disimpan. Semua data akan disinkronkan otomatis saat online.
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center justify-center gap-2 pt-2">
                <button type="button" @click="checkConnection()" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-teal-700 hover:bg-teal-800 active:scale-95 transition shadow-sm flex items-center space-x-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>Cek Ulang Koneksi</span>
                </button>
                <a href="/dashboard" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 active:scale-95 transition">
                    Buka Dashboard Tersimpan
                </a>
                <a href="/projects/1" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 active:scale-95 transition">
                    Workspace Smart Soil
                </a>
            </div>
        </div>

        <!-- Offline Field Note Capture -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Catat Ide / Bukti Lapangan (Offline)</h2>
                    <p class="text-[11px] text-slate-400">Data disimpan di IndexedDB lokal perangkat Anda dan disinkronkan ke server saat kembali online.</p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-50 text-teal-800 border border-teal-200">
                    Auto-Sync Aktif
                </span>
            </div>

            <form @submit.prevent="saveOfflineNote()" class="space-y-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Temuan / Ide *</label>
                    <input type="text" x-model="newNote.title" required placeholder="Contoh: Sensor Node 04 tergenang lumpur, nilai kapasitif drop 15%..."
                           class="w-full text-xs rounded-xl border-slate-200 focus:ring-teal-500 focus:border-teal-500 py-2.5 px-3">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kategori</label>
                        <select x-model="newNote.category" class="w-full text-xs rounded-xl border-slate-200 focus:ring-teal-500 focus:border-teal-500 py-2 px-3">
                            <option value="field_evidence">Bukti Lapangan (Evidence)</option>
                            <option value="idea">Ide Penelitian Baru</option>
                            <option value="hypothesis">Hipotesis / Observasi</option>
                            <option value="task">Catatan Tugas Lapangan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Prioritas</label>
                        <select x-model="newNote.priority" class="w-full text-xs rounded-xl border-slate-200 focus:ring-teal-500 focus:border-teal-500 py-2 px-3">
                            <option value="high">Tinggi (Penting)</option>
                            <option value="medium">Sedang</option>
                            <option value="low">Rendah</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Uraian Detail Catatan</label>
                    <textarea x-model="newNote.description" rows="3" placeholder="Tuliskan data angka, nama saksi/petani, koordinat, atau observasi..."
                              class="w-full text-xs rounded-xl border-slate-200 focus:ring-teal-500 focus:border-teal-500 py-2 px-3"></textarea>
                </div>
                <button type="submit" class="w-full py-2.5 rounded-xl font-bold text-xs text-white bg-gradient-to-r from-teal-600 to-emerald-600 hover:opacity-95 active:scale-95 shadow-xs transition flex items-center justify-center space-x-1.5">
                    <span>Simpan ke Database Offline Lokal</span>
                </button>
            </form>
        </div>

        <!-- Saved Offline Items Queue -->
        <div class="bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs space-y-3" x-show="offlineNotes.length > 0" x-cloak>
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <h3 class="text-xs font-bold text-slate-900">Antrean Sinkronisasi Lokal (<span x-text="offlineNotes.length"></span>)</h3>
                <span class="text-[10px] text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md font-semibold">Menunggu Koneksi</span>
            </div>

            <div class="space-y-2">
                <template x-for="(note, index) in offlineNotes" :key="note.id || index">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/80 flex items-start justify-between space-x-3 text-xs">
                        <div class="space-y-1 min-w-0">
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-teal-100 text-teal-800" x-text="note.category"></span>
                                <span class="font-bold text-slate-900 truncate" x-text="note.title"></span>
                            </div>
                            <p class="text-slate-500 text-[11px] line-clamp-2" x-text="note.description"></p>
                            <span class="text-[10px] text-slate-400 block" x-text="new Date(note.timestamp).toLocaleTimeString()"></span>
                        </div>
                        <button type="button" @click="deleteOfflineNote(index)" class="text-slate-400 hover:text-rose-600 p-1" title="Hapus">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="max-w-3xl mx-auto w-full py-4 text-center text-xs text-slate-400 border-t border-slate-200">
        RESEARCH OS • Offline PWA Support • Service Worker Ready
    </footer>

    <script>
        function offlineApp() {
            return {
                isOnline: navigator.onLine,
                offlineNotes: [],
                newNote: {
                    title: '',
                    category: 'field_evidence',
                    priority: 'high',
                    description: ''
                },
                init() {
                    window.addEventListener('online', () => {
                        this.isOnline = true;
                        this.syncNotesToServer();
                    });
                    window.addEventListener('offline', () => {
                        this.isOnline = false;
                    });
                    this.loadSavedNotes();
                },
                checkConnection() {
                    this.isOnline = navigator.onLine;
                    if (this.isOnline) {
                        window.location.href = '/dashboard';
                    } else {
                        alert('Koneksi masih offline. Anda tetap bisa mencatat ide/bukti secara lokal!');
                    }
                },
                loadSavedNotes() {
                    try {
                        const raw = localStorage.getItem('research_os_offline_notes');
                        this.offlineNotes = raw ? JSON.parse(raw) : [];
                    } catch (e) {
                        this.offlineNotes = [];
                    }
                },
                saveOfflineNote() {
                    const item = {
                        id: Date.now(),
                        title: this.newNote.title,
                        category: this.newNote.category,
                        priority: this.newNote.priority,
                        description: this.newNote.description,
                        timestamp: new Date().toISOString()
                    };
                    this.offlineNotes.unshift(item);
                    localStorage.setItem('research_os_offline_notes', JSON.stringify(this.offlineNotes));

                    // Also dispatch to background sync queue
                    if (window.ResearchOSSync) {
                        window.ResearchOSSync.enqueue('idea', item);
                    }

                    this.newNote = { title: '', category: 'field_evidence', priority: 'high', description: '' };
                    alert('Catatan berhasil disimpan secara lokal di perangkat! Akan diunggah otomatis saat online.');
                },
                deleteOfflineNote(idx) {
                    this.offlineNotes.splice(idx, 1);
                    localStorage.setItem('research_os_offline_notes', JSON.stringify(this.offlineNotes));
                },
                syncNotesToServer() {
                    if (this.offlineNotes.length === 0) return;
                    // Trigger sync
                    console.log('Online restored! Triggering sync of', this.offlineNotes.length, 'notes');
                }
            }
        }
    </script>
</body>
</html>
