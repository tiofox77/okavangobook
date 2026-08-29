<?php

namespace App\Support;

/**
 * Parser leve de User-Agent (sem dependências externas).
 * Deteta tipo de dispositivo, browser, sistema operativo e bots.
 */
class VisitorAgent
{
    public static function parse(?string $ua): array
    {
        $ua = $ua ?? '';

        return [
            'is_bot' => self::isBot($ua),
            'device_type' => self::deviceType($ua),
            'browser' => self::browser($ua),
            'platform' => self::platform($ua),
        ];
    }

    public static function isBot(string $ua): bool
    {
        if (trim($ua) === '') {
            return true;
        }

        return (bool) preg_match(
            '/bot|crawl|spider|slurp|mediapartners|bingpreview|facebookexternalhit|whatsapp|telegrambot|discordbot|preview|monitor|curl|wget|python-requests|axios|go-http|java\/|headless|lighthouse|pingdom|uptime|semrush|ahrefs|petalbot|yandex|baidu|dataprovider|dotbot/i',
            $ua
        );
    }

    public static function deviceType(string $ua): string
    {
        if (self::isBot($ua)) {
            return 'bot';
        }

        // Tablets primeiro (Android sem "Mobile" costuma ser tablet)
        if (preg_match('/iPad|Tablet|PlayBook|Silk|Kindle/i', $ua)
            || (preg_match('/Android/i', $ua) && !preg_match('/Mobile/i', $ua))) {
            return 'tablet';
        }

        if (preg_match('/Mobile|iPhone|iPod|Android.*Mobile|Windows Phone|BlackBerry|Opera Mini|IEMobile/i', $ua)) {
            return 'mobile';
        }

        return 'desktop';
    }

    public static function browser(string $ua): string
    {
        // A ordem importa: UAs do Chrome contêm "Safari", do Edge contêm "Chrome", etc.
        return match (true) {
            (bool) preg_match('/Edg[A-Z]?\//i', $ua) => 'Edge',
            (bool) preg_match('/OPR\/|Opera/i', $ua) => 'Opera',
            (bool) preg_match('/SamsungBrowser/i', $ua) => 'Samsung Internet',
            (bool) preg_match('/UCBrowser/i', $ua) => 'UC Browser',
            (bool) preg_match('/CriOS|Chrome|Chromium/i', $ua) => 'Chrome',
            (bool) preg_match('/FxiOS|Firefox/i', $ua) => 'Firefox',
            (bool) preg_match('/MSIE|Trident/i', $ua) => 'Internet Explorer',
            (bool) preg_match('/Safari/i', $ua) => 'Safari',
            default => 'Outro',
        };
    }

    public static function platform(string $ua): string
    {
        return match (true) {
            (bool) preg_match('/Windows NT|Windows Phone|Win64|Win32/i', $ua) => 'Windows',
            (bool) preg_match('/iPhone|iPad|iPod/i', $ua) => 'iOS',
            (bool) preg_match('/Mac OS X|Macintosh/i', $ua) => 'macOS',
            (bool) preg_match('/Android/i', $ua) => 'Android',
            (bool) preg_match('/CrOS/i', $ua) => 'ChromeOS',
            (bool) preg_match('/Linux/i', $ua) => 'Linux',
            default => 'Outro',
        };
    }
}
