// Service Worker for Kopi Ancol PWA
const CACHE_NAME = 'kopi-ancol-v1.0.0';
const OFFLINE_URL = '/offline';

// Assets to cache
const STATIC_ASSETS = [
  '/',
  '/offline',
  '/css/app.css',
  '/js/app.js',
  '/manifest.json',
  '/favicon.ico'
];

// API endpoints to cache (GET only)
const API_CACHE = [
  '/api/products',
  '/api/categories'
];

// Install Service Worker
self.addEventListener('install', (event) => {
  console.log('[SW] Installing Service Worker...');
  
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('[SW] Caching static assets');
        return cache.addAll(STATIC_ASSETS);
      })
      .then(() => {
        console.log('[SW] Skip waiting');
        return self.skipWaiting();
      })
  );
});

// Activate Service Worker
self.addEventListener('activate', (event) => {
  console.log('[SW] Activating Service Worker...');
  
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            console.log('[SW] Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => {
      console.log('[SW] Claiming clients');
      return self.clients.claim();
    })
  );
});

// Fetch event handler
self.addEventListener('fetch', (event) => {
  const request = event.request;
  const url = new URL(request.url);
  
  // Skip non-GET requests
  if (request.method !== 'GET') {
    return;
  }
  
  // Skip Laravel CSRF and auth endpoints
  if (url.pathname.includes('/admin') || 
      url.pathname.includes('/owner') || 
      url.pathname.includes('/login') ||
      url.pathname.includes('/logout')) {
    return;
  }
  
  // HTML pages (Network first, fallback to cache)
  if (request.headers.get('accept').includes('text/html')) {
    event.respondWith(
      fetch(request)
        .then((response) => {
          // Cache the fresh response
          const responseClone = response.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(request, responseClone);
          });
          return response;
        })
        .catch(() => {
          // If network fails, try cache
          return caches.match(request)
            .then((cachedResponse) => {
              if (cachedResponse) {
                return cachedResponse;
              }
              // If no cache, show offline page
              return caches.match(OFFLINE_URL);
            });
        })
    );
    return;
  }
  
  // API requests (Network first, no cache fallback)
  if (url.pathname.includes('/api/') || url.pathname.includes('/cart/') || url.pathname.includes('/order/')) {
    event.respondWith(
      fetch(request)
        .then((response) => {
          // Cache API responses for offline mode
          const responseClone = response.clone();
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(request, responseClone);
          });
          return response;
        })
        .catch(() => {
          return caches.match(request);
        })
    );
    return;
  }
  
  // Static assets (Cache first, network fallback)
  event.respondWith(
    caches.match(request)
      .then((cachedResponse) => {
        if (cachedResponse) {
          return cachedResponse;
        }
        return fetch(request)
          .then((response) => {
            const responseClone = response.clone();
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(request, responseClone);
            });
            return response;
          });
      })
  );
});

// Push Notification handler
self.addEventListener('push', (event) => {
  console.log('[SW] Push Notification received');
  
  let data = {
    title: 'Kopi Ancol',
    body: 'Ada notifikasi baru',
    icon: '/images/icons/icon-192x192.png',
    badge: '/images/icons/icon-96x96.png',
    tag: 'notification',
    vibrate: [200, 100, 200],
    data: {
      url: '/'
    }
  };
  
  if (event.data) {
    try {
      data = event.data.json();
    } catch (e) {
      data.body = event.data.text();
    }
  }
  
  event.waitUntil(
    self.registration.showNotification(data.title, {
      body: data.body,
      icon: data.icon || '/images/icons/icon-192x192.png',
      badge: data.badge || '/images/icons/icon-96x96.png',
      tag: data.tag || 'notification',
      vibrate: data.vibrate || [200, 100, 200],
      data: data.data || { url: '/' },
      actions: [
        {
          action: 'open',
          title: 'Buka',
          icon: '/images/icons/icon-96x96.png'
        },
        {
          action: 'close',
          title: 'Tutup',
          icon: '/images/icons/icon-96x96.png'
        }
      ]
    })
  );
});

// Notification click handler
self.addEventListener('notificationclick', (event) => {
  console.log('[SW] Notification click received');
  
  event.notification.close();
  
  const urlToOpen = event.notification.data?.url || '/';
  
  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true })
      .then((clientList) => {
        // Check if there's already a window/tab open with the target URL
        for (const client of clientList) {
          if (client.url === urlToOpen && 'focus' in client) {
            return client.focus();
          }
        }
        // If not, open a new window/tab
        if (clients.openWindow) {
          return clients.openWindow(urlToOpen);
        }
      })
  );
});