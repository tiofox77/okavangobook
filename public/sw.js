/**
 * Service Worker — KiandaStay PWA
 *
 * Estratégias:
 *  - Navegação (HTML): network-first com fallback para /offline.html.
 *  - Estáticos (css/js/img/fonts): stale-while-revalidate.
 *  - Nunca faz cache de /admin, APIs ou pedidos não-GET.
 */
const VERSION = 'v3-branding';
const STATIC_CACHE = `kianda-static-${VERSION}`;
const RUNTIME_CACHE = `kianda-runtime-${VERSION}`;

const PRECACHE = [
    '/offline.html',
    '/assets/img/branding/kiandastay-logo.png',
    '/assets/img/branding/kiandastay-mark.png',
    '/assets/img/favicon-16.png',
    '/assets/img/favicon-32.png',
    '/assets/img/icon-192.png',
    '/assets/img/icon-512.png',
    '/assets/img/pwa/icon-192.png',
    '/assets/img/pwa/icon-512.png',
    '/assets/img/pwa/maskable-512.png',
];

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE).then((cache) => cache.addAll(PRECACHE)).then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys.filter((k) => ![STATIC_CACHE, RUNTIME_CACHE].includes(k)).map((k) => caches.delete(k))
            )
        ).then(() => self.clients.claim())
    );
});

function isStaticAsset(url) {
    return /\.(css|js|png|jpe?g|gif|svg|webp|ico|woff2?|ttf|eot)$/i.test(url.pathname);
}

self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Só GET, mesma origem, e fora do painel admin
    if (request.method !== 'GET' || url.origin !== self.location.origin) return;
    if (url.pathname.startsWith('/admin') || url.pathname.startsWith('/livewire')) return;

    // Navegação (páginas HTML): network-first -> offline fallback
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request).catch(() => caches.match('/offline.html'))
        );
        return;
    }

    // Estáticos: stale-while-revalidate
    if (isStaticAsset(url)) {
        event.respondWith(
            caches.open(RUNTIME_CACHE).then(async (cache) => {
                const cached = await cache.match(request);
                const network = fetch(request)
                    .then((resp) => {
                        if (resp && resp.status === 200) cache.put(request, resp.clone());
                        return resp;
                    })
                    .catch(() => cached);
                return cached || network;
            })
        );
    }
});
