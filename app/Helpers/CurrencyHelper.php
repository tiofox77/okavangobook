<?php

declare(strict_types=1);

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Formata valor para moeda angolana (Kwanza)
     *
     * @param float|int|string $value Valor a ser formatado
     * @param bool $showSymbol Se deve mostrar o símbolo KZ
     * @param int $decimals Número de casas decimais
     * @return string Valor formatado
     */
    public static function formatKwanza($value, bool $showSymbol = true, int $decimals = 2): string
    {
        $numericValue = is_numeric($value) ? (float) $value : 0;
        
        $formatted = number_format($numericValue, $decimals, ',', '.');
        
        return $showSymbol ? $formatted . ' KZ' : $formatted;
    }
    
    /**
     * Formata valor monetário de forma mais legível
     *
     * @param float|int|string $value Valor a ser formatado
     * @param bool $showSymbol Se deve mostrar o símbolo KZ
     * @return string Valor formatado
     */
    public static function formatMoney($value, bool $showSymbol = true): string
    {
        $numericValue = is_numeric($value) ? (float) $value : 0;
        
        // Para valores grandes, usa formato abreviado
        if ($numericValue >= 1000000) {
            $formatted = number_format($numericValue / 1000000, 1, ',', '.') . 'M';
        } elseif ($numericValue >= 1000) {
            $formatted = number_format($numericValue / 1000, 1, ',', '.') . 'K';
        } else {
            $formatted = number_format($numericValue, 0, ',', '.');
        }
        
        return $showSymbol ? $formatted . ' KZ' : $formatted;
    }
    
    /**
     * Converte string de moeda para float
     *
     * @param string $currencyString String com formato de moeda
     * @return float Valor numérico
     */
    public static function parseCurrency(string $currencyString): float
    {
        // Remove símbolos de moeda e espaços
        $cleaned = preg_replace('/[^\d,.]/', '', $currencyString);
        
        // Converte vírgula para ponto (formato brasileiro/angolano)
        $cleaned = str_replace(',', '.', $cleaned);
        
        return (float) $cleaned;
    }
}
