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

        // Sem preferência explícita do visitante, o primeiro acesso é sempre
        // em português. A escolha PT/EN continua guardada na sessão.
        if (!$locale) {
            $locale = config('app.locale', 'pt');
        }

        if (!in_array($locale, self::SUPPORTED, true)) {
            $locale = config('app.locale', 'pt');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
