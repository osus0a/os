const CACHE_NAME = "os1-cache-v1";
const STATIC_ASSETS = [
    '/assets/css/plugins/style.css',
    '/assets/css/plugins/bootstrap-switch-button.min.css',
    '/assets/css/plugins/datepicker-bs5.min.css',
    '/assets/css/plugins/flatpickr.min.css',
    '/assets/css/customizer.css',
    '/css/custome.css',
    '/css/custom-color.css',
    '/assets/css/style.css',
    '/assets/css/style-dark.css',
    '/assets/css/style-rtl.css',
    '/css/responsive.css',
    '/assets/css/nprogress.css',

    '/assets/fonts/tabler-icons.min.css',
    '/assets/fonts/feather.css',
    '/assets/fonts/fontawesome.css',
    '/assets/fonts/material.css',

    '/js/jquery.min.js',
    '/js/icons.js',
    '/assets/js/plugins/popper.min.js',
    '/assets/js/plugins/perfect-scrollbar.min.js',
    '/assets/js/plugins/bootstrap.min.js',
    '/assets/js/plugins/feather.min.js',
    '/assets/js/plugins/simplebar.min.js',
    '/assets/js/dash.js',
    '/assets/js/plugins/simple-datatables.js',
    '/assets/js/plugins/bootstrap-switch-button.min.js',
    '/assets/js/plugins/sweetalert2.all.min.js',
    '/assets/js/plugins/datepicker-full.min.js',
    '/assets/js/plugins/flatpickr.min.js',
    '/assets/js/plugins/choices.min.js',
    '/js/jquery.form.js',
    '/assets/js/layout-tab.js',
    '/js/custom.js',
    '/assets/js/nprogress.js'
];

// Install event - cache static assets
self.addEventListener("install", event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Caching static assets');
                return cache.addAll(STATIC_ASSETS);
            })
            .catch(error => {
                console.error('Error during service worker install:', error);
            })
    );
    self.skipWaiting();
});

// Activate event - clean up old caches
self.addEventListener("activate", event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.filter(cacheName => {
                    return cacheName !== CACHE_NAME;
                }).map(cacheName => {
                    console.log('Deleting old cache:', cacheName);
                    return caches.delete(cacheName);
                })
            );
        })
    );
    return self.clients.claim();
});

// Fetch event - serve from cache, fallback to network
self.addEventListener("fetch", event => {
    // Skip cross-origin requests
    if (!event.request.url.startsWith(self.location.origin)) {
        return;
    }

    // Skip non-GET requests
    if (event.request.method !== 'GET') {
        return;
    }

    // Skip API requests
    if (event.request.url.includes('/api/')) {
        return;
    }

    // Cache-first strategy for static assets
    if (isStaticAsset(event.request.url)) {
        event.respondWith(
            caches.match(event.request).then(cachedResponse => {
                if (cachedResponse) {
                    return cachedResponse;
                }

                return fetch(event.request).then(response => {
                    // Don't cache non-successful responses
                    if (!response || response.status !== 200) {
                        return response;
                    }

                    // Clone the response as it can only be consumed once
                    const responseToCache = response.clone();

                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, responseToCache);
                    });

                    return response;
                });
            })
        );
    } else {
        // Network-first strategy for dynamic content
        event.respondWith(
            fetch(event.request)
                .then(response => {
                    return response;
                })
                .catch(() => {
                    return caches.match(event.request);
                })
        );
    }
});

// Helper function to check if URL is a static asset
function isStaticAsset(url) {
    const staticExtensions = [
        '.css', '.js', '.woff', '.woff2', '.ttf', '.svg', '.png', '.jpg',
        '.jpeg', '.gif', '.ico', '.json'
    ];

    return staticExtensions.some(ext => url.endsWith(ext));
}


