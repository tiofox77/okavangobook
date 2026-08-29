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
    private const WRITABLE = [
        'app_name', 'app_description', 'app_keywords', 'app_currency',
        'contact_email', 'contact_phone', 'contact_address',
        'social_facebook', 'social_instagram', 'social_twitter',
        'default_language',
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
