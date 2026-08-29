<?php

namespace App\Support;

/**
 * Converte um código de país ISO-3166 alpha-2 (ex.: "AO", "PT") no
 * respetivo emoji de bandeira, combinando os dois símbolos indicadores
 * regionais. Não requer imagens nem dependências.
 */
class Flag
{
    public static function emoji(?string $code): string
    {
        $code = strtoupper(trim((string) $code));

        if (strlen($code) !== 2 || ! ctype_alpha($code)) {
            return '🌍';
        }

        $offset = 0x1F1E6 - ord('A');

        return mb_convert_encoding('&#' . (ord($code[0]) + $offset) . ';', 'UTF-8', 'HTML-ENTITIES')
             . mb_convert_encoding('&#' . (ord($code[1]) + $offset) . ';', 'UTF-8', 'HTML-ENTITIES');
    }
}
