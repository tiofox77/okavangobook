<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckForSystemUpdates extends Command
{
    protected $signature = 'system:check-updates';
    protected $description = 'Verifica se há novas atualizações disponíveis no GitHub e notifica os administradores';

    public function handle(): int
    {
        $this->info('Verificando atualizações do sistema...');

        $repositoryUrl = Setting::get('github_repository_url', '');

        if (empty($repositoryUrl)) {
            $this->warn('URL do repositório GitHub não configurada. Configure em Admin > Atualizações.');
            return Command::SUCCESS;
        }

        // Extract owner/repo
        $repo = '';
        $url = rtrim($repositoryUrl, '.git');
        if (preg_match('/github\.com\/([^\/]+\/[^\/]+)/', $url, $matches)) {
            $repo = $matches[1];
        }

        if (empty($repo)) {
            $this->error('URL do repositório inválida: ' . $repositoryUrl);
            return Command::FAILURE;
        }

        // Get current version
        $versionFile = base_path('version.txt');
        $currentVersion = file_exists($versionFile) ? trim(file_get_contents($versionFile)) : '1.0.0';

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'OkavangoBook-Updater',
            ])->timeout(30)->get("https://api.github.com/repos/{$repo}/releases");

            if (!$response->successful()) {
                $this->error('Falha ao acessar API do GitHub: HTTP ' . $response->status());
                return Command::FAILURE;
            }

            $releases = $response->json();

            if (empty($releases)) {
                $this->info('Nenhuma release encontrada no repositório.');
                return Command::SUCCESS;
            }

            $latestRelease = $releases[0];
            $latestVersion = ltrim($latestRelease['tag_name'] ?? '', 'v');

            if (empty($latestVersion)) {
                $this->warn('Não foi possível determinar a versão da última release.');
                return Command::SUCCESS;
            }

            if (version_compare($latestVersion, $currentVersion, '>')) {
                $this->info("Nova versão disponível: {$latestVersion} (atual: {$currentVersion})");

                // Check if we already notified about this version
                $lastNotifiedVersion = Setting::get('last_notified_update_version', '');

                if ($lastNotifiedVersion !== $latestVersion) {
                    $this->notifyAdmins($latestVersion, $currentVersion, $latestRelease);
                    Setting::set('last_notified_update_version', $latestVersion, 'updates', 'string', 'Última versão notificada', false);
                    $this->info('Administradores notificados sobre a nova versão.');
                } else {
                    $this->info('Administradores já foram notificados sobre esta versão.');
                }
            } else {
                $this->info("Sistema atualizado. Versão atual: {$currentVersion}");
            }

            // Store last check timestamp
            Setting::set('last_update_check', now()->toDateTimeString(), 'updates', 'string', 'Última verificação de atualizações', false);

            return Command::SUCCESS;

        } catch (\Exception $e) {
            Log::error('Erro ao verificar atualizações: ' . $e->getMessage());
            $this->error('Erro: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Notify all admin users about available update
     */
    protected function notifyAdmins(string $latestVersion, string $currentVersion, array $releaseData): void
    {
        $admins = User::role('Admin')->get();

        foreach ($admins as $admin) {
            Notification::createForUser(
                $admin->id,
                'system_update',
                'Nova Atualização Disponível',
                "Uma nova versão do sistema está disponível: v{$latestVersion} (atual: v{$currentVersion}). " .
                ($releaseData['name'] ?? '') . ' - Acesse Atualizações do Sistema para instalar.',
                'fas fa-download',
                route('admin.updates')
            );
        }

        Log::info("Administradores notificados sobre nova versão: {$latestVersion}");
    }
}
