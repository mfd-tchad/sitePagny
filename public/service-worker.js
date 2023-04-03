// This is the service worker with the combined offline experience (Offline page + Offline copy of pages)

const CACHE = "pwa-offline-pages";

importScripts('https://storage.googleapis.com/workbox-cdn/releases/5.1.2/workbox-sw.js');

// TODO: replace the following with the correct offline fallback page i.e.: const offlineFallbackPage = "offline.html";
var urlsToCache = [
  '/',
  '/viepratique',
  '/activites',
  '/contact',
  '/alaune',
  '/actualite',
  '/evenements',
  '/mairie',
  '/associations',
  '/patrimoine',
  '/historique',
  '/eglise',
  '/conseilmunicipal',
  '/urbanisme',
  '/demarches',
  '/jeunesse',
  '/reserve',
  '/sportsetloisirs',
  '/videgrenier'
];

self.addEventListener("message", (event) => {
  if (event.data && event.data.type === "SKIP_WAITING") {
    self.skipWaiting();
  }
});

self.addEventListener('install', async (event) => {
  event.waitUntil(
    caches.open(CACHE)
      .then((cache) => cache.addAll(urlsToCache))
  );
});

if (workbox.navigationPreload.isSupported()) {
  workbox.navigationPreload.enable();
}

workbox.routing.registerRoute(
  new RegExp('/*'),
  new workbox.strategies.StaleWhileRevalidate({
    cacheName: CACHE
  })
);

self.addEventListener('fetch', (event) => {
  if (event.request.mode === 'navigate') {
    event.respondWith((async () => {
      try {
        const preloadResp = await event.preloadResponse;

        if (preloadResp) {
          return preloadResp;
        }

        const networkResp = await fetch(event.request);
        return networkResp;
      } catch (error) {

        const cache = await caches.open(CACHE);
        const cachedResp = await cache.match(urlsToCache);
        return cachedResp;
      }
    })());
  }
});