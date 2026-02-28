/**
 * Domiradios Service Worker
 * Caches static assets and radio logos for offline/faster loading
 */
const CACHE_NAME = 'domiradios-v1';
const STATIC_ASSETS = [
  '/',
  '/offline',
];

// Install - precache critical assets
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(STATIC_ASSETS))
  );
  self.skipWaiting();
});

// Activate - clean old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
    )
  );
  self.clients.claim();
});

// Fetch - cache-first for static assets, network-first for pages
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);

  // Skip non-GET requests and streaming URLs
  if (request.method !== 'GET') return;
  if (url.pathname.startsWith('/api/')) return;
  if (url.pathname.startsWith('/livewire/')) return;

  // Cache-first for static assets (images, CSS, JS, fonts)
  if (
    url.pathname.startsWith('/storage/radios/') ||
    url.pathname.startsWith('/images/') ||
    url.pathname.startsWith('/build/') ||
    url.pathname.match(/\.(css|js|woff2?|ttf|eot|svg|png|jpg|jpeg|webp|ico|gif)$/)
  ) {
    event.respondWith(
      caches.match(request).then((cached) => {
        if (cached) return cached;
        return fetch(request).then((response) => {
          if (response.ok) {
            const clone = response.clone();
            caches.open(CACHE_NAME).then((cache) => cache.put(request, clone));
          }
          return response;
        }).catch(() => caches.match('/offline'));
      })
    );
    return;
  }

  // Network-first for HTML pages
  if (request.headers.get('Accept')?.includes('text/html')) {
    event.respondWith(
      fetch(request)
        .then((response) => {
          const clone = response.clone();
          caches.open(CACHE_NAME).then((cache) => cache.put(request, clone));
          return response;
        })
        .catch(() => caches.match(request).then((cached) => cached || caches.match('/')))
    );
  }
});
