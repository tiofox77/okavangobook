<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Resolve país/cidade a partir de um IP, de forma tolerante a falhas.
 *
 * Estratégia:
 *  1. Se o Cloudflare enviou CF-IPCountry, usa esse código de país (instantâneo).
 *  2. Caso contrário, consulta ip-api.com (grátis, sem chave) com cache de 24h
 *     por IP. É chamado no terminate() do middleware — já depois de a resposta
 *     ter sido enviada — portanto não afeta a latência percebida pelo utilizador.
 *  3. Qualquer erro/timeout devolve o melhor valor disponível sem lançar exceção.
 */
class GeoLocator
{
    public static function locate(?string $ip, ?string $cfCountry = null): array
    {
        $fallback = ['country' => null, 'country_code' => $cfCountry, 'city' => null];

        if (empty($ip)) {
            return $fallback;
        }

        if (self::isPrivate($ip)) {
            return ['country' => 'Local', 'country_code' => null, 'city' => 'Rede local'];
        }

        return Cache::remember("geo_ip_{$ip}", now()->addHours(24), function () use ($ip, $cfCountry, $fallback) {
            try {
                $resp = Http::timeout(2)->retry(1, 200)->get("http://ip-api.com/json/{$ip}", [
                    'fields' => 'status,country,countryCode,city',
                    'lang' => 'pt-BR',
                ]);

                if ($resp->ok() && ($resp->json('status') === 'success')) {
                    return [
                        'country' => $resp->json('country') ?: $fallback['country'],
                        'country_code' => $resp->json('countryCode') ?: $cfCountry,
                        'city' => $resp->json('city') ?: null,
                    ];
                }
            } catch (\Throwable $e) {
                // silencioso — geolocalização é best-effort
            }

            return $fallback;
        });
    }

    private static function isPrivate(string $ip): bool
    {
        return ! filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        );
    }
}
