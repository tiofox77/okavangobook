<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Rastreio de visitas
    |--------------------------------------------------------------------------
    | 'enabled'    liga/desliga a gravação de visitas (middleware TrackPageVisit).
    | 'geolocate'  resolve país/cidade a partir do IP (via CF-IPCountry e, em
    |              fallback, ip-api.com com cache de 24h). Corre em terminate(),
    |              já depois da resposta ser enviada, logo não afeta a latência.
    | 'store_bots' se falso, não grava tráfego identificado como bot/crawler.
    */
    'enabled' => (bool) env('ANALYTICS_TRACKING', true),
    'geolocate' => (bool) env('ANALYTICS_GEOLOCATE', true),
    'store_bots' => (bool) env('ANALYTICS_STORE_BOTS', true),
];
