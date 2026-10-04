/**
 * Research OS Service Worker
 * Comprehensive PWA & Offline Support with Intelligent Caching & Offline Fallback
 */

const CACHE_NAME = 'research-os-v1';
const OFFLINE_URL = '/offline';

const PRECACHE_ASSETS = [
    OFFLINE_URL,
    '/manifest.webmanifest',
    '/manifest.json',
    '/icons/icon.svg',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png',
    '/icons/apple-touch-icon.png',
    '/favicon.ico',
];

// Install: Cache essential app shell & offline page
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            console.log('[Research OS SW] Pre-caching offline shell and assets');
            return cache.addAll(PRECACHE_ASSETS).catch((err) => {
                console.warn('[Research OS SW] Some precache assets failed:', err);
            });
        }).then(() => self.skipWaiting())
    );
});

// Activate: Clean up older cache versions and take control immediately
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((name) => {
                    if (name !== CACHE_NAME) {
                        console.log('[Research OS SW] Deleting obsolete cache:', name);
                        return caches.delete(name);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch: Strategy depending on request type
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests (mutations handled via IndexedDB sync queue in client)
    if (request.method !== 'GET') {
        return;
    }

    // Skip browser extension schemes or cross-origin chrome-extension://
    if (!url.protocol.startsWith('http')) {
        return;
    }

    // 1. Navigation Requests (HTML Pages like /dashboard, /projects/1, etc.)
    // Strategy: Network-First with Cache Fallback and Offline Page
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((networkResponse) => {
                    // Only cache successful standard responses
                    if (networkResponse && networkResponse.status === 200) {
                        const responseClone = networkResponse.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, responseClone);
                        });
                    }
                    return networkResponse;
                })
                .catch(async () => {
                    // Network failed — try to serve cached version of this exact page
                    const cachedResponse = await caches.match(request);
                    if (cachedResponse) {
                        return cachedResponse;
                    }

                    // Try dashboard if root
                    const cachedDashboard = await caches.match('/dashboard');
                    if (cachedDashboard) {
                        return cachedDashboard;
                    }

                    // Ultimate fallback: Dedicated offline page
                    const offlinePage = await caches.match(OFFLINE_URL);
                    if (offlinePage) {
                        return offlinePage;
                    }

                    return new Response(
                        '<h1>Research OS Offline</h1><p>Koneksi internet Anda terputus dan halaman ini belum tersimpan.</p>',
                        { headers: { 'Content-Type': 'text/html; charset=utf-8' } }
                    );
                })
        );
        return;
    }

    // 2. Static Assets (CSS, JS, Fonts, Images)
    // Strategy: Stale-While-Revalidate
    const isStaticAsset = (
        url.pathname.startsWith('/build/') ||
        url.pathname.startsWith('/icons/') ||
        url.pathname.includes('/fonts/') ||
        url.hostname.includes('fonts.bunny.net') ||
        request.destination === 'style' ||
        request.destination === 'script' ||
        request.destination === 'image' ||
        request.destination === 'font'
    );

    if (isStaticAsset) {
        event.respondWith(
            caches.open(CACHE_NAME).then(async (cache) => {
                const cachedResponse = await cache.match(request);

                // Fetch network in background to update cache
                const fetchPromise = fetch(request)
                    .then((networkResponse) => {
                        if (networkResponse && networkResponse.status === 200) {
                            cache.put(request, networkResponse.clone());
                        }
                        return networkResponse;
                    })
                    .catch(() => cachedResponse);

                // Return cached version immediately if available, otherwise wait for network
                return cachedResponse || fetchPromise;
            })
        );
        return;
    }

    // 3. Default: Network with Cache Fallback
    event.respondWith(
        fetch(request)
            .then((networkResponse) => {
                if (networkResponse && networkResponse.status === 200) {
                    const responseClone = networkResponse.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(request, responseClone));
                }
                return networkResponse;
            })
            .catch(() => caches.match(request))
    );
});

// Background Sync (if supported)
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-offline-research') {
        console.log('[Research OS SW] Background sync event triggered');
        event.waitUntil(
            self.clients.matchAll().then((clients) => {
                clients.forEach((client) => {
                    client.postMessage({ type: 'TRIGGER_OFFLINE_SYNC' });
                });
            })
        );
    }
});
