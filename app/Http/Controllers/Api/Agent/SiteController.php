<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AgentAuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SiteController extends Controller
{
    /**
     * Definições que o agente pode alterar. Deliberadamente NÃO inclui
     * segredos (api_key), dados bancários nem interruptores de sistema
     * (maintenance_mode, debug_mode).
     */
    private const WRITABLE = [
        // Identidade e SEO
        'app_name', 'app_description', 'app_keywords', 'app_currency',
        'meta_description', 'meta_keywords',
        // Contactos
        'contact_email', 'contact_phone', 'contact_address',
        // Redes sociais
        'social_facebook', 'social_instagram', 'social_twitter', 'social_youtube',
        // Localização/idioma
        'default_language', 'app_language', 'app_timezone',
    ];

    /** Limites práticos de comprimento por chave. */
    private const MAX_LENGTH = [
        'app_name' => 120,
        'app_description' => 500,
        'meta_description' => 320,
        'app_keywords' => 500,
        'meta_keywords' => 500,
        'contact_email' => 190,
        'contact_phone' => 60,
        'contact_address' => 300,
    ];

    public function __construct(private AgentAuditService $audit) {}

    public function status()
    {
        $checks = [
            'database' => $this->check(fn () => DB::select('select 1')),
            'storage_writable' => is_writable(storage_path()),
            'migrations' => Schema::hasTable('agent_tokens'),
        ];

        return response()->json([
            'status' => in_array(false, $checks, true) ? 'degraded' : 'ok',
            'version' => trim((string) @file_get_contents(base_path('version.txt'))) ?: 'unknown',
            'environment' => app()->environment(),
            'time' => now()->toIso8601String(),
            'checks' => $checks,
        ]);
    }

    public function settings()
    {
        return response()->json(['data' => Arr::only(Setting::getPublicSettings(), self::WRITABLE)]);
    }

    public function updateSettings(Request $request)
    {
        $payload = $request->validate([
            'settings' => ['required', 'array', 'min:1'],
            'settings.*' => ['nullable'],
            'dry_run' => ['sometimes', 'boolean'],
        ]);
        $requested = Arr::only($payload['settings'], self::WRITABLE);

        if ($requested === []) {
            return response()->json(['message' => 'Nenhuma configuração permitida recebida.'], 422);
        }

        // Validação por chave: evita gravar SEO/contactos inválidos
        $erros = [];
        foreach ($requested as $key => $value) {
            if ($value === null) {
                continue;
            }
            if (!is_scalar($value)) {
                $erros[$key][] = 'O valor tem de ser texto.';
                continue;
            }
            $texto = (string) $value;
            if (isset(self::MAX_LENGTH[$key]) && mb_strlen($texto) > self::MAX_LENGTH[$key]) {
                $erros[$key][] = 'Máximo de ' . self::MAX_LENGTH[$key] . ' caracteres.';
            }
            if ($key === 'contact_email' && $texto !== '' && !filter_var($texto, FILTER_VALIDATE_EMAIL)) {
                $erros[$key][] = 'Email inválido.';
            }
            if (str_starts_with($key, 'social_') && $texto !== '' && !preg_match('#^https?://#i', $texto)) {
                $erros[$key][] = 'Indique o endereço completo, começado por https://';
            }
        }
        if ($erros !== []) {
            return response()->json(['message' => 'Valores inválidos.', 'errors' => $erros], 422);
        }

        $before = collect(array_keys($requested))->mapWithKeys(fn ($key) => [$key => Setting::get($key)])->all();
        $dryRun = (bool) ($payload['dry_run'] ?? false);

        if (!$dryRun) {
            foreach ($requested as $key => $value) {
                Setting::set($key, $value, 'general', is_bool($value) ? 'boolean' : 'string', 'Atualizado pela Agent API', true);
            }
        }

        $this->audit->record($request, 'site.settings.updated', Setting::class, $before, $requested, 200, $dryRun);

        return response()->json([
            'data' => $requested,
            'dry_run' => $dryRun,
            'message' => $dryRun ? 'Pré-visualização; nenhuma alteração aplicada.' : 'Configurações atualizadas.',
        ]);
    }

    private function check(callable $callback): bool
    {
        try {
            $callback();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
