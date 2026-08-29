<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use App\Support\GeoLocator;
use App\Support\VisitorAgent;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Grava uma visita por cada carregamento de página pública (GET HTML 200).
 *
 * O trabalho é feito em terminate(), que corre DEPOIS de a resposta ser
 * enviada ao navegador — logo a geolocalização e a escrita em BD não
 * aumentam o tempo de resposta. Tudo está protegido por try/catch: o
 * rastreio nunca pode quebrar o site.
 */
class TrackPageVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        try {
            if (! config('analytics.enabled', true)) {
                return;
            }

            if (! $this->shouldTrack($request, $response)) {
                return;
            }

            $ua = (string) $request->userAgent();
            $agent = VisitorAgent::parse($ua);

            if ($agent['is_bot'] && ! config('analytics.store_bots', true)) {
                return;
            }

            // País via Cloudflare (se existir) — instantâneo, sem chamada externa.
            $cfCountry = $request->header('CF-IPCountry');
            $cfCountry = ($cfCountry && strlen($cfCountry) === 2 && strtoupper($cfCountry) !== 'XX')
                ? strtoupper($cfCountry)
                : null;

            $geo = ['country' => null, 'country_code' => $cfCountry, 'city' => null];
            if (config('analytics.geolocate', true) && ! $agent['is_bot']) {
                $geo = GeoLocator::locate($request->ip(), $cfCountry);
            }

            // Referrer — ignora navegação interna (mesmo host).
            $referrer = $request->headers->get('referer');
            $referrerHost = $referrer ? parse_url($referrer, PHP_URL_HOST) : null;
            if ($referrerHost && str_contains(strtolower($referrerHost), strtolower($request->getHost()))) {
                $referrer = null;
                $referrerHost = null;
            }

            PageVisit::create([
                'session_id' => $request->hasSession() ? $request->session()->getId() : null,
                'user_id' => $request->user()?->id,
                'path' => mb_substr($request->path(), 0, 512),
                'url' => mb_substr($request->fullUrl(), 0, 1024),
                'method' => $request->method(),
                'referrer' => $referrer ? mb_substr($referrer, 0, 1024) : null,
                'referrer_host' => $referrerHost ? mb_substr($referrerHost, 0, 255) : null,
                'ip' => $request->ip(),
                'country' => $geo['country'],
                'country_code' => $geo['country_code'],
                'city' => $geo['city'],
                'device_type' => $agent['device_type'],
                'browser' => $agent['browser'],
                'platform' => $agent['platform'],
                'language' => substr((string) $request->getPreferredLanguage(), 0, 10) ?: null,
                'is_bot' => $agent['is_bot'],
                'user_agent' => mb_substr($ua, 0, 1000),
            ]);
        } catch (\Throwable $e) {
            report($e); // nunca propagar — o site tem prioridade
        }
    }

    /**
     * Só rastreamos páginas públicas: GET, HTML, 200, não-AJAX,
     * excluindo admin, api, livewire, autenticação e assets.
     */
    private function shouldTrack(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET')) {
            return false;
        }

        if ($request->ajax() || $request->pjax() || $request->wantsJson()) {
            return false;
        }

        $status = $response->getStatusCode();
        if ($status < 200 || $status >= 300) {
            return false;
        }

        $contentType = (string) $response->headers->get('Content-Type');
        if ($contentType !== '' && ! str_contains($contentType, 'text/html')) {
            return false;
        }

        $ignore = [
            'admin', 'admin/*', 'api/*', 'livewire/*', 'sanctum/*',
            'login', 'logout', 'register', 'password', 'password/*',
            'email/*', 'reset-password/*', 'forgot-password',
            'sitemap*', 'robots.txt', 'favicon.ico', 'manifest.webmanifest',
            'sw.js', 'up', 'storage/*', 'assets/*', 'build/*',
        ];

        foreach ($ignore as $pattern) {
            if ($request->is($pattern)) {
                return false;
            }
        }

        return true;
    }
}
