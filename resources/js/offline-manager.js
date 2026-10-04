/**
 * Research OS — PWA & Offline Synchronization Engine
 * Handles Service Worker registration, PWA install prompts,
 * real-time online/offline indicators, and IndexedDB sync queue.
 */

class OfflineSyncEngine {
    constructor() {
        this.dbName = 'ResearchOS_OfflineDB';
        this.dbVersion = 1;
        this.db = null;
        this.deferredPrompt = null;
        this.isOnline = navigator.onLine;
        this.pendingCount = 0;

        this.init();
    }

    async init() {
        await this.initDatabase();
        this.registerServiceWorker();
        this.setupNetworkListeners();
        this.setupPwaInstallPrompt();
        this.setupOfflineFormInterceptors();
        this.updatePendingCount();

        // Check if there are queued items to sync immediately
        if (this.isOnline) {
            this.processSyncQueue();
        }
    }

    /**
     * Initialize IndexedDB for offline transactions
     */
    initDatabase() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(this.dbName, this.dbVersion);

            request.onupgradeneeded = (e) => {
                const db = e.target.result;
                if (!db.objectStoreNames.contains('sync_queue')) {
                    const store = db.createObjectStore('sync_queue', { keyPath: 'id', autoIncrement: true });
                    store.createIndex('action', 'action', { unique: false });
                    store.createIndex('created_at', 'created_at', { unique: false });
                }
                if (!db.objectStoreNames.contains('offline_notes')) {
                    db.createObjectStore('offline_notes', { keyPath: 'id', autoIncrement: true });
                }
            };

            request.onsuccess = (e) => {
                this.db = e.target.result;
                resolve(this.db);
            };

            request.onerror = (e) => {
                console.warn('[Offline Engine] IndexedDB error:', e);
                resolve(null);
            };
        });
    }

    /**
     * Register Service Worker
     */
    registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((reg) => {
                        console.log('[Research OS PWA] Service Worker registered with scope:', reg.scope);

                        // Check for updates
                        reg.addEventListener('updatefound', () => {
                            const newWorker = reg.installing;
                            newWorker.addEventListener('statechange', () => {
                                if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                    this.showToast('Versi baru Research OS tersedia! Muat ulang untuk memperbarui.', 'info');
                                }
                            });
                        });
                    })
                    .catch((err) => {
                        console.warn('[Research OS PWA] Service Worker registration failed:', err);
                    });
            });

            // Listen for messages from SW
            navigator.serviceWorker.addEventListener('message', (event) => {
                if (event.data && event.data.type === 'TRIGGER_OFFLINE_SYNC') {
                    this.processSyncQueue();
                }
            });
        }
    }

    /**
     * Setup online / offline events
     */
    setupNetworkListeners() {
        window.addEventListener('online', () => {
            this.isOnline = true;
            this.renderOfflineBar();
            this.showToast('Koneksi internet kembali. Memulai sinkronisasi data...', 'success');
            this.processSyncQueue();
            window.dispatchEvent(new CustomEvent('connection-change', { detail: { online: true } }));
        });

        window.addEventListener('offline', () => {
            this.isOnline = false;
            this.renderOfflineBar();
            this.showToast('Anda sedang offline. Research OS tetap aktif, data tersimpan di perangkat.', 'warning');
            window.dispatchEvent(new CustomEvent('connection-change', { detail: { online: false } }));
        });

        // Initial render
        this.renderOfflineBar();
    }

    /**
     * Non-intrusive Offline Status Indicator (Bottom toast/pill instead of covering header)
     */
    renderOfflineBar() {
        let bar = document.getElementById('research-os-offline-banner');

        if (!this.isOnline) {
            if (!bar) {
                bar = document.createElement('div');
                bar.id = 'research-os-offline-banner';
                bar.className = 'fixed bottom-4 left-4 z-50 bg-amber-900/95 backdrop-blur-md border border-amber-600/80 text-white px-4 py-2.5 rounded-2xl text-xs font-semibold shadow-2xl flex items-center space-x-3 transition-all';
                document.body.appendChild(bar);
            }
            bar.innerHTML = `
                <div class="flex items-center space-x-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400 animate-ping shrink-0"></span>
                    <span class="text-xs"><strong>Mode Offline</strong>: Data tersimpan di HP/PC.</span>
                    <span id="offline-pending-badge" class="bg-amber-800/80 px-2 py-0.5 rounded text-[10px] font-bold">
                        ${this.pendingCount} Antrean
                    </span>
                    <a href="/offline" class="underline text-amber-200 hover:text-white text-[11px] ml-1">Buka Catatan</a>
                </div>
            `;
            bar.style.display = 'flex';
        } else if (bar) {
            bar.style.display = 'none';
        }
    }

    /**
     * Setup PWA Install Prompt
     */
    setupPwaInstallPrompt() {
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            this.deferredPrompt = e;
            window.dispatchEvent(new CustomEvent('pwa-installable'));
        });

        window.addEventListener('appinstalled', () => {
            this.deferredPrompt = null;
            this.showToast('Research OS berhasil dipasang di perangkat Anda.', 'success');
            const installBtn = document.getElementById('pwa-install-banner');
            if (installBtn) installBtn.remove();
        });
    }

    /**
     * Render Install Floating Banner (optional, unobtrusive)
     */
    renderPwaInstallButton() {
        // Kept clean without intrusive auto-popup; install is available via User menu & PWA triggers
    }

    promptInstall() {
        if (!this.deferredPrompt) {
            this.showToast('Gunakan menu browser "Tambahkan ke Layar Utama" untuk menginstal.', 'info');
            return;
        }

        this.deferredPrompt.prompt();
        this.deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('[PWA] User accepted install');
            }
            this.deferredPrompt = null;
            const banner = document.getElementById('pwa-install-banner');
            if (banner) banner.remove();
        });
    }

    /**
     * Intercept form submissions when offline
     */
    setupOfflineFormInterceptors() {
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (!navigator.onLine && form && form.method && form.method.toUpperCase() === 'POST') {
                // If it's a file upload form with file selected, warn user
                const fileInput = form.querySelector('input[type="file"]');
                if (fileInput && fileInput.files && fileInput.files.length > 0) {
                    alert('Upload berkas besar memerlukan koneksi internet. Berkas akan dapat diunggah saat Anda kembali online.');
                    return;
                }

                e.preventDefault();
                this.captureFormSubmission(form);
            }
        });
    }

    async captureFormSubmission(form) {
        const formData = new FormData(form);
        const actionUrl = form.action;
        const formObj = {};

        formData.forEach((value, key) => {
            // Skip files and tokens
            if (key !== '_token' && !(value instanceof File)) {
                formObj[key] = value;
            }
        });

        const queueItem = {
            url: actionUrl,
            method: form.method || 'POST',
            data: formObj,
            created_at: new Date().toISOString()
        };

        await this.enqueue('form_submit', queueItem);

        this.showToast('Disimpan offline. Perubahan akan disinkronkan otomatis saat tersambung internet.', 'success');

        // Close any open Alpine modals if present
        const closeBtn = form.closest('[x-show]')?.querySelector('button[type="button"]');
        if (closeBtn) closeBtn.click();

        // Reset form inputs
        form.reset();
    }

    /**
     * Add item to IndexedDB sync queue
     */
    enqueue(action, data) {
        return new Promise((resolve) => {
            if (!this.db) {
                // Fallback to localStorage
                const localQueue = JSON.parse(localStorage.getItem('research_os_sync_queue') || '[]');
                localQueue.push({ action, data, created_at: new Date().toISOString() });
                localStorage.setItem('research_os_sync_queue', JSON.stringify(localQueue));
                this.updatePendingCount();
                resolve(true);
                return;
            }

            const tx = this.db.transaction('sync_queue', 'readwrite');
            const store = tx.objectStore('sync_queue');
            store.add({ action, data, created_at: new Date().toISOString() });

            tx.oncomplete = () => {
                this.updatePendingCount();
                resolve(true);
            };
            tx.onerror = () => resolve(false);
        });
    }

    /**
     * Update pending badge
     */
    async updatePendingCount() {
        if (!this.db) {
            const localQueue = JSON.parse(localStorage.getItem('research_os_sync_queue') || '[]');
            this.pendingCount = localQueue.length;
        } else {
            const count = await new Promise((resolve) => {
                const tx = this.db.transaction('sync_queue', 'readonly');
                const store = tx.objectStore('sync_queue');
                const req = store.count();
                req.onsuccess = () => resolve(req.result);
                req.onerror = () => resolve(0);
            });
            this.pendingCount = count;
        }

        const badge = document.getElementById('offline-pending-badge');
        if (badge) {
            badge.innerText = `${this.pendingCount} Tertunda`;
        }
    }

    /**
     * Process sync queue when online
     */
    async processSyncQueue() {
        if (!navigator.onLine) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        if (!this.db) {
            // Process localStorage queue
            const localQueue = JSON.parse(localStorage.getItem('research_os_sync_queue') || '[]');
            if (localQueue.length === 0) return;

            console.log('[Offline Engine] Syncing', localQueue.length, 'items from localStorage');
            for (const item of localQueue) {
                await this.sendSyncItem(item, csrfToken);
            }
            localStorage.removeItem('research_os_sync_queue');
            this.updatePendingCount();
            this.showToast('Semua data offline berhasil disinkronkan ke server.', 'success');
            return;
        }

        const items = await new Promise((resolve) => {
            const tx = this.db.transaction('sync_queue', 'readonly');
            const store = tx.objectStore('sync_queue');
            const req = store.getAll();
            req.onsuccess = () => resolve(req.result);
            req.onerror = () => resolve([]);
        });

        if (!items || items.length === 0) return;

        console.log('[Offline Engine] Syncing', items.length, 'items from IndexedDB');
        let successCount = 0;

        for (const item of items) {
            const ok = await this.sendSyncItem(item, csrfToken);
            if (ok) {
                successCount++;
                const delTx = this.db.transaction('sync_queue', 'readwrite');
                delTx.objectStore('sync_queue').delete(item.id);
            }
        }

        this.updatePendingCount();
        if (successCount > 0) {
            this.showToast(`${successCount} data penelitian offline berhasil disinkronkan ke server.`, 'success');
        }
    }

    async sendSyncItem(item, csrfToken) {
        if (!item || !item.data) return true;

        try {
            const url = item.data.url || (item.action === 'idea' ? '/ideas' : null);
            if (!url) return true;

            const payload = item.data.data || item.data;

            const response = await fetch(url, {
                method: item.data.method || 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                    'Accept': 'application/json, text/html'
                },
                body: JSON.stringify(payload)
            });

            return response.ok || response.status === 302;
        } catch (e) {
            console.warn('[Offline Engine] Sync item failed, will retry later:', e);
            return false;
        }
    }

    /**
     * Clean toast notification
     */
    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        const colorClasses = {
            success: 'bg-emerald-800 text-white border-emerald-600',
            warning: 'bg-amber-800 text-white border-amber-600',
            info: 'bg-slate-900 text-white border-slate-700'
        }[type] || 'bg-slate-900 text-white';

        toast.className = `fixed bottom-5 left-1/2 -translate-x-1/2 z-50 px-4 py-2.5 rounded-2xl shadow-xl border text-xs font-semibold flex items-center space-x-2 animate-fade-in transition-all ${colorClasses}`;
        toast.innerHTML = `
            <span>${message}</span>
        `;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }
}

// Global instance
window.ResearchOSSync = new OfflineSyncEngine();
window.triggerPwaInstall = () => window.ResearchOSSync.promptInstall();
