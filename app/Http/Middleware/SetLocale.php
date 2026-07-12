<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Idiomas suportados.
     */
    public const SUPPORTED = ['en', 'pt'];

    public function handle(Request $request, Closure $next)
    {
        $locale = Session::get('locale');

        // Sem preferência na sessão: usa o idioma padrão definido no admin,
        // caindo para o config (por padrão inglês).
        if (!$locale) {
            $locale = \App\Models\Setting::get('default_language', config('app.locale', 'en'));
        }

        if (!in_array($locale, self::SUPPORTED, true)) {
            $locale = config('app.locale', 'en');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
