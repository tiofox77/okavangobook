<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use ZipArchive;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class SystemUpdates extends Component
{
    // Active tab
    public string $activeTab = 'settings';
    
    // GitHub Settings
    public string $githubRepo = '';
    public string $repositoryUrl = '';
    
    // Version Information
    public string $currentVersion = '';
    public string $latestVersion = '';
    public bool $updateAvailable = false;
    public array $latestReleaseData = [];
    
    // Update Process
    public bool $isUpdating = false;
    public bool $isCheckingForUpdates = false;
    public array $updateProgress = [];
    public array $updateLog = [];
    
    // System Requirements
    public array $systemRequirements = [];
    public array $requirementsStatus = ['passed' => 0, 'warnings' => 0, 'failed' => 0];
    public bool $isCheckingRequirements = false;
    public bool $requirementsMet = false;

    // Confirmation modal
    public bool $showConfirmModal = false;
    public string $confirmMessage = '';
    public string $confirmAction = '';

    /**
     * Initialize component
     */
    public function mount(): void
    {
        $this->loadSettings();
        $this->getCurrentVersion();
        
        // Initialize requirements check if needed
        if (empty($this->systemRequirements)) {
            $this->requirementsMet = false;
        }
        
        // Ensure requirementsStatus is properly initialized
        if (!is_array($this->requirementsStatus)) {
            $this->requirementsStatus = ['passed' => 0, 'warnings' => 0, 'failed' => 0];
        }
        
        // Ensure updateLog is properly initialized as array
        if (!is_array($this->updateLog)) {
            $this->updateLog = [];
        }
    }

    /**
     * Load settings from database
     */
    protected function loadSettings(): void
    {
        $this->repositoryUrl = Setting::get('github_repository_url', '');
        $this->githubRepo = $this->extractRepoFromUrl($this->repositoryUrl);
    }

    /**
     * Extract repository owner/name from GitHub URL
     */
    private function extractRepoFromUrl(string $url): string
    {
        if (empty($url)) {
            return '';
        }

        // Remove .git suffix if present
        $url = rtrim($url, '.git');
        
        // Extract owner/repo from various GitHub URL formats
        if (preg_match('/github\.com\/([^\/]+\/[^\/]+)/', $url, $matches)) {
            return $matches[1];
        }
        
        return '';
    }

    /**
     * Get current application version
     */
    protected function getCurrentVersion(): void
    {
        $versionFile = base_path('version.txt');
        if (file_exists($versionFile)) {
            $this->currentVersion = trim(file_get_contents($versionFile));
        } else {
            $this->currentVersion = '1.0.0';
        }
    }

    /**
     * Get latest release from GitHub repository
     */
    private function getLatestRelease(string $repo): array|null
    {
        try {
            $response = Http::withHeaders([
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'OkavangoBook-Updater'
            ])->get("https://api.github.com/repos/{$repo}/releases");

            if ($response->successful()) {
                $releases = $response->json();
                
                if (empty($releases)) {
                    return null;
                }

                return $releases[0]; // Return the latest (first) release
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Error getting latest release: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check for available updates
     */
    public function checkForUpdates(): void
    {
        if (empty($this->repositoryUrl)) {
            session()->flash('error', 'URL do repositório GitHub não configurada.');
            return;
        }

        $this->githubRepo = $this->extractRepoFromUrl($this->repositoryUrl);
        
        if (empty($this->githubRepo)) {
            session()->flash('error', 'URL do repositório inválida. Use o formato: https://github.com/owner/repo');
            return;
        }

        $this->isCheckingForUpdates = true;

        try {
            $latestRelease = $this->getLatestRelease($this->githubRepo);

            if ($latestRelease) {
                $this->latestVersion = ltrim($latestRelease['tag_name'] ?? '', 'v');
                $this->latestReleaseData = [
                    'name' => $latestRelease['name'] ?? '',
                    'body' => $latestRelease['body'] ?? '',
                    'published_at' => $latestRelease['published_at'] ?? '',
                    'download_url' => $latestRelease['zipball_url'] ?? '',
                    'html_url' => $latestRelease['html_url'] ?? ''
                ];

                // Compare versions
                if (version_compare($this->latestVersion, $this->currentVersion, '>')) {
                    $this->updateAvailable = true;
                    session()->flash('success', "Nova versão disponível: {$this->latestVersion} (atual: {$this->currentVersion})");
                } else {
                    $this->updateAvailable = false;
                    session()->flash('info', "Aplicação está atualizada! Versão atual: {$this->currentVersion}");
                }
            } else {
                session()->flash('error', 'Repositório não possui releases ou não foi possível acessá-lo.');
            }
        } catch (\Exception $e) {
            Log::error('Error checking updates: ' . $e->getMessage());
            session()->flash('error', 'Erro ao verificar atualizações: ' . $e->getMessage());
        } finally {
            $this->isCheckingForUpdates = false;
        }
    }

    /**
     * Start the update process
     */
    public function startUpdate(): void
    {
        if (!$this->updateAvailable) {
            session()->flash('error', 'Nenhuma atualização disponível.');
            return;
        }

        // Check system requirements if not already checked
        if (empty($this->systemRequirements)) {
            $this->checkSystemRequirements();
        }

        if (!$this->requirementsMet) {
            $failedCount = $this->requirementsStatus['failed'] ?? 0;
            $warningCount = $this->requirementsStatus['warnings'] ?? 0;
            
            if ($failedCount > 0) {
                session()->flash('error', "Requisitos críticos não atendidos ({$failedCount} falhas). Verifique a aba 'Requisitos do Sistema'.");
            } else {
                session()->flash('warning', "Alguns requisitos geraram avisos ({$warningCount} avisos). Continuando com a atualização...");
                // Allow update to proceed with warnings
            }
            
            if ($failedCount > 0) {
                return;
            }
        }

        $this->isUpdating = true;
        $this->updateProgress = [];
        $this->updateLog = [];
        $this->addToLog('Iniciando processo de atualização...');

        try {
            // Step 1: Create backup if enabled
            $this->updateProgress['criar_backup'] = 'processing';
            $this->addToLog('Criando backup...');
            $this->createBackup();
            $this->updateProgress['criar_backup'] = 'completed';

            // Step 2: Enable maintenance mode
            $this->updateProgress['modo_manutencao'] = 'processing';
            $this->addToLog('Ativando modo de manutenção...');
            $this->enableMaintenanceMode();
            $this->updateProgress['modo_manutencao'] = 'completed';

            // Step 3: Download update
            $this->updateProgress['baixar_atualizacao'] = 'processing';
            $this->addToLog('Baixando atualização...');
            $updateFile = $this->downloadUpdate();
            $this->updateProgress['baixar_atualizacao'] = 'completed';

            // Step 4: Extract and validate
            $this->updateProgress['extrair_arquivos'] = 'processing';
            $this->addToLog('Extraindo arquivos...');
            $this->extractUpdate($updateFile);
            $this->updateProgress['extrair_arquivos'] = 'completed';

            // Step 5: Install update
            $this->updateProgress['instalar_atualizacao'] = 'processing';
            $this->addToLog('Instalando atualização...');
            $this->installUpdate();
            $this->updateProgress['instalar_atualizacao'] = 'completed';

            // Step 6: Run migrations
            $this->updateProgress['executar_migracoes'] = 'processing';
            $this->addToLog('Executando migrações...');
            $this->runMigrations();
            $this->updateProgress['executar_migracoes'] = 'completed';

            // Step 7: Finalize
            $this->updateProgress['finalizar'] = 'processing';
            $this->addToLog('Finalizando...');
            $this->finalizeUpdate();
            $this->updateProgress['finalizar'] = 'completed';

            // Step 8: Disable maintenance mode
            $this->updateProgress['desativar_manutencao'] = 'processing';
            $this->addToLog('Desativando modo de manutenção...');
            $this->disableMaintenanceMode();
            $this->updateProgress['desativar_manutencao'] = 'completed';

            $this->addToLog('Atualização concluída com sucesso!');
            $this->isUpdating = false;
            
            // Update current version
            $this->currentVersion = $this->latestVersion;
            $this->updateAvailable = false;
            
            session()->flash('success', 'Atualização instalada com sucesso!');
            
        } catch (\Exception $e) {
            Log::error('Update failed: ' . $e->getMessage());
            $this->addToLog('Erro durante a atualização: ' . $e->getMessage());
            
            // Try to restore from backup
            $this->restoreFromBackup();
            
            $this->isUpdating = false;
            session()->flash('error', 'Erro durante a atualização: ' . $e->getMessage());
        }
    }

    /**
     * Create system backup
     */
    protected function createBackup(): void
    {
        $this->addToLog('Criando backup do sistema...');

        try {
            $backupDir = storage_path('backups');
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            $timestamp = date('Y-m-d_H-i-s');
            $backupFile = $backupDir . "/backup_{$timestamp}.zip";

            $zip = new ZipArchive();
            if ($zip->open($backupFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                throw new \Exception('Não foi possível criar arquivo de backup');
            }

            // Backup important directories
            $directoriesToBackup = [
                'app',
                'config', 
                'database',
                'resources',
                'routes',
                'public',
            ];

            foreach ($directoriesToBackup as $dir) {
                $this->addDirectoryToZip(base_path($dir), $zip, $dir);
            }

            // Backup important files
            $filesToBackup = [
                '.env',
                'composer.json',
                'composer.lock',
                'package.json',
                'version.txt',
            ];

            foreach ($filesToBackup as $file) {
                $filePath = base_path($file);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $file);
                }
            }

            $zip->close();

            Setting::set('last_backup_file', $backupFile, 'backup', 'string', 'Último arquivo de backup', false);
            Setting::set('last_backup_date', now()->toDateTimeString(), 'backup', 'string', 'Data do último backup', false);

            $this->addToLog("Backup criado: " . basename($backupFile));
        } catch (\Exception $e) {
            throw new \Exception('Erro ao criar backup: ' . $e->getMessage());
        }
    }

    /**
     * Add directory to ZIP recursively
     */
    protected function addDirectoryToZip(string $dir, ZipArchive $zip, string $zipPath): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                $zip->addEmptyDir($zipPath . '/' . $iterator->getSubPathName());
            } elseif ($file->isFile()) {
                $zip->addFile($file->getRealPath(), $zipPath . '/' . $iterator->getSubPathName());
            }
        }
    }

    /**
     * Download update from GitHub
     */
    protected function downloadUpdate(): string
    {
        $this->addToLog('Baixando atualização do GitHub...');

        try {
            // Use public download URL from releases
            $downloadUrl = $this->latestReleaseData['download_url'] ?? '';
            
            if (empty($downloadUrl)) {
                throw new \Exception('URL de download não encontrada');
            }

            $updateFile = storage_path('app/updates/update_' . time() . '.zip');
            
            // Create updates directory if it doesn't exist
            if (!is_dir(dirname($updateFile))) {
                mkdir(dirname($updateFile), 0755, true);
            }

            // Download the zip file
            $response = Http::timeout(300)->get($downloadUrl);
            
            if (!$response->successful()) {
                throw new \Exception('Falha ao baixar atualização');
            }

            file_put_contents($updateFile, $response->body());
            
            $this->addToLog('Atualização baixada: ' . basename($updateFile));
            
            return $updateFile;
        } catch (\Exception $e) {
            throw new \Exception('Erro ao baixar atualização: ' . $e->getMessage());
        }
    }

    /**
     * Extract update files
     */
    protected function extractUpdate(string $updateFile): void
    {
        $this->addToLog('Extraindo arquivos de atualização...');

        try {
            $extractDir = storage_path('app/updates/extracted');
            
            // Clean extraction directory
            if (is_dir($extractDir)) {
                $this->deleteDirectory($extractDir);
            }
            mkdir($extractDir, 0755, true);

            $zip = new ZipArchive();
            if ($zip->open($updateFile) !== TRUE) {
                throw new \Exception('Não foi possível abrir arquivo de atualização');
            }

            $zip->extractTo($extractDir);
            $zip->close();

            $this->addToLog('Arquivos extraídos com sucesso');
        } catch (\Exception $e) {
            throw new \Exception('Erro ao extrair atualização: ' . $e->getMessage());
        }
    }

    /**
     * Install the update
     */
    protected function installUpdate(): void
    {
        $this->addToLog('Instalando atualização...');

        try {
            $extractDir = storage_path('app/updates/extracted');
            
            // Find the extracted repository folder
            $dirs = array_diff(scandir($extractDir), array('.', '..'));
            $repoDir = $extractDir . '/' . reset($dirs);

            if (!is_dir($repoDir)) {
                throw new \Exception('Diretório de atualização não encontrado');
            }

            // Copy files to application root
            $this->copyDirectory($repoDir, base_path(), [
                'storage',
                'node_modules',
                'vendor',
                '.git',
                '.env',
                'bootstrap/cache',
            ]);

            // Update composer dependencies if composer.json changed
            if (file_exists($repoDir . '/composer.json')) {
                $this->addToLog('Atualizando dependências do Composer...');
                exec('cd ' . base_path() . ' && composer install --no-dev --optimize-autoloader', $output, $returnCode);
                
                if ($returnCode !== 0) {
                    Log::warning('Composer install returned non-zero exit code', ['output' => $output]);
                }
            }

            $this->addToLog('Arquivos instalados com sucesso');
        } catch (\Exception $e) {
            throw new \Exception('Erro durante instalação: ' . $e->getMessage());
        }
    }

    /**
     * Copy directory contents
     */
    protected function copyDirectory(string $source, string $destination, array $exclude = []): void
    {
        if (!is_dir($source)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $relativePath = $iterator->getSubPathName();
            $skip = false;

            // Check if path should be excluded
            foreach ($exclude as $excludePattern) {
                if (strpos($relativePath, $excludePattern) === 0) {
                    $skip = true;
                    break;
                }
            }

            if ($skip) {
                continue;
            }

            $destPath = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();

            if ($item->isDir()) {
                if (!is_dir($destPath)) {
                    mkdir($destPath, 0755, true);
                }
            } else {
                $destDir = dirname($destPath);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                copy($item->getRealPath(), $destPath);
            }
        }
    }

    /**
     * Run database migrations
     */
    protected function runMigrations(): void
    {
        $this->addToLog('Executando migrações de base de dados...');

        try {
            Artisan::call('migrate', ['--force' => true]);
            $this->addToLog('Migrações executadas com sucesso');
        } catch (\Exception $e) {
            Log::error('Migration failed: ' . $e->getMessage());
            $this->addToLog('Aviso: Erro ao executar migrações - ' . $e->getMessage());
        }

        // Clear caches
        $this->addToLog('Limpando cache...');
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
        } catch (\Exception $e) {
            Log::error('Cache clear failed: ' . $e->getMessage());
        }
    }

    /**
     * Update version file
     */
    protected function updateVersion(): void
    {
        $versionFile = base_path('version.txt');
        file_put_contents($versionFile, $this->latestVersion);
        $this->addToLog('Versão atualizada para ' . $this->latestVersion);
    }

    /**
     * Enable maintenance mode
     */
    protected function enableMaintenanceMode(): void
    {
        try {
            Artisan::call('down');
            $this->addToLog('Modo de manutenção ativado');
        } catch (\Exception $e) {
            Log::error('Failed to enable maintenance mode: ' . $e->getMessage());
        }
    }

    /**
     * Disable maintenance mode
     */
    protected function disableMaintenanceMode(): void
    {
        try {
            Artisan::call('up');
            $this->addToLog('Modo de manutenção desativado');
        } catch (\Exception $e) {
            Log::error('Failed to disable maintenance mode: ' . $e->getMessage());
        }
    }

    /**
     * Restore from backup
     */
    protected function restoreFromBackup(): void
    {
        $this->addToLog('Tentando restaurar do backup...');

        try {
            $lastBackupFile = Setting::get('last_backup_file');
            
            if (!$lastBackupFile || !file_exists($lastBackupFile)) {
                $this->addToLog('Nenhum backup encontrado para restauração');
                return;
            }

            // Implementation would go here for backup restoration
            $this->addToLog('Restauração do backup não implementada nesta versão');
        } catch (\Exception $e) {
            Log::error('Backup restoration failed: ' . $e->getMessage());
            $this->addToLog('Erro ao restaurar do backup: ' . $e->getMessage());
        }
    }

    /**
     * Check system requirements
     */
    public function checkSystemRequirements(): void
    {
        $this->isCheckingRequirements = true;
        $this->systemRequirements = [];
        $this->requirementsStatus = ['passed' => 0, 'warnings' => 0, 'failed' => 0];

        try {
            // Check PHP version
            $phpVersion = PHP_VERSION;
            $minPhpVersion = '8.1.0';
            $phpOk = version_compare($phpVersion, $minPhpVersion, '>=');
            
            $this->systemRequirements[] = [
                'name' => 'PHP Version',
                'required' => '>= ' . $minPhpVersion,
                'current' => $phpVersion,
                'status' => $phpOk ? 'passed' : 'failed'
            ];
            if ($phpOk) $this->requirementsStatus['passed']++; else $this->requirementsStatus['failed']++;

            // Check required extensions
            $requiredExtensions = ['zip', 'curl', 'openssl', 'json', 'fileinfo'];
            foreach ($requiredExtensions as $extension) {
                $loaded = extension_loaded($extension);
                $this->systemRequirements[] = [
                    'name' => 'PHP Extension: ' . $extension,
                    'required' => 'Enabled',
                    'current' => $loaded ? 'Enabled' : 'Not Found',
                    'status' => $loaded ? 'passed' : 'failed'
                ];
                if ($loaded) $this->requirementsStatus['passed']++; else $this->requirementsStatus['failed']++;
            }

            // Check directory permissions
            $directories = [
                storage_path(),
                storage_path('app'),
                storage_path('logs'),
                base_path('bootstrap/cache'),
            ];

            foreach ($directories as $directory) {
                $writable = is_writable($directory);
                $this->systemRequirements[] = [
                    'name' => 'Directory Writable: ' . basename($directory),
                    'required' => 'Writable',
                    'current' => $writable ? 'Writable' : 'Not Writable',
                    'status' => $writable ? 'passed' : 'failed'
                ];
                if ($writable) $this->requirementsStatus['passed']++; else $this->requirementsStatus['failed']++;
            }

            // Check disk space
            $freeSpace = disk_free_space(base_path());
            $minSpace = 100 * 1024 * 1024; // 100MB
            $spaceOk = $freeSpace > $minSpace;
            
            $this->systemRequirements[] = [
                'name' => 'Free Disk Space',
                'required' => '>= 100MB',
                'current' => $this->formatBytes($freeSpace),
                'status' => $spaceOk ? 'passed' : 'warning'
            ];
            if ($spaceOk) $this->requirementsStatus['passed']++; else $this->requirementsStatus['warnings']++;

            // Check Git availability
            $gitAvailable = !empty(shell_exec('git --version 2>&1'));
            $this->systemRequirements[] = [
                'name' => 'Git Command',
                'required' => 'Available',
                'current' => $gitAvailable ? 'Available' : 'Not Found',
                'status' => $gitAvailable ? 'passed' : 'warning'
            ];
            if ($gitAvailable) $this->requirementsStatus['passed']++; else $this->requirementsStatus['warnings']++;

            // Update requirementsMet based on requirements status
            $this->requirementsMet = ($this->requirementsStatus['failed'] === 0);

        } catch (\Exception $e) {
            Log::error('Error checking system requirements: ' . $e->getMessage());
            session()->flash('error', 'Erro ao verificar requisitos do sistema: ' . $e->getMessage());
            $this->requirementsMet = false;
        } finally {
            $this->isCheckingRequirements = false;
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int|float $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Save GitHub repository settings
     */
    public function saveGitHubSettings(): void
    {
        $this->validate([
            'repositoryUrl' => 'required|url|regex:/github\.com/i',
        ], [
            'repositoryUrl.required' => 'URL do repositório é obrigatória',
            'repositoryUrl.url' => 'URL inválida',
            'repositoryUrl.regex' => 'Deve ser uma URL do GitHub'
        ]);

        try {
            Setting::set('github_repository_url', $this->repositoryUrl, 'updates', 'string', 'GitHub repository URL for updates', false);
            
            // Extract and save repo owner/name
            $this->githubRepo = $this->extractRepoFromUrl($this->repositoryUrl);
            
            session()->flash('success', 'Configurações do repositório salvas com sucesso!');
        } catch (\Exception $e) {
            Log::error('Error saving GitHub settings: ' . $e->getMessage());
            session()->flash('error', 'Erro ao salvar configurações: ' . $e->getMessage());
        }
    }

    /**
     * Test repository connectivity
     */
    public function testRepository(): void
    {
        if (empty($this->repositoryUrl)) {
            session()->flash('error', 'Insira uma URL de repositório primeiro.');
            return;
        }

        $repo = $this->extractRepoFromUrl($this->repositoryUrl);
        
        if (empty($repo)) {
            session()->flash('error', 'URL do repositório inválida.');
            return;
        }

        try {
            // Test if repository exists
            $response = Http::withHeaders([
                'Accept' => 'application/vnd.github.v3+json',
                'User-Agent' => 'OkavangoBook-Updater'
            ])->get("https://api.github.com/repos/{$repo}");

            if ($response->successful()) {
                $repoData = $response->json();
                
                // Check if it's a public repository
                if ($repoData['private'] ?? false) {
                    session()->flash('warning', 'Repositório encontrado, mas é privado. Para atualizações automáticas, use um repositório público.');
                } else {
                    // Use the same method as checkForUpdates
                    $latestRelease = $this->getLatestRelease($repo);
                    
                    if ($latestRelease) {
                        $tagName = $latestRelease['tag_name'] ?? 'N/A';
                        session()->flash('success', "Repositório válido! Última release: {$tagName}");
                    } else {
                        session()->flash('warning', 'Repositório encontrado, mas não possui releases. Crie uma release primeiro.');
                    }
                }
            } elseif ($response->status() === 404) {
                session()->flash('error', 'Repositório não encontrado. Verifique se a URL está correta.');
            } else {
                session()->flash('error', 'Erro ao acessar repositório: HTTP ' . $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Error testing repository: ' . $e->getMessage());
            session()->flash('error', 'Erro ao testar repositório: ' . $e->getMessage());
        }
    }

    /**
     * Cancel GitHub settings edit
     */
    public function cancelGitHubEdit(): void
    {
        $this->loadSettings();
    }

    /**
     * Show confirmation modal
     */
    public function showConfirmation(string $action, string $message): void
    {
        $this->confirmAction = $action;
        $this->confirmMessage = $message;
        $this->showConfirmModal = true;
    }

    /**
     * Execute confirmed action
     */
    public function executeConfirmedAction(): void
    {
        $this->showConfirmModal = false;

        switch ($this->confirmAction) {
            case 'startUpdate':
                $this->startUpdate();
                break;
            case 'checkUpdates':
                $this->checkForUpdates();
                break;
        }

        $this->confirmAction = '';
        $this->confirmMessage = '';
    }

    /**
     * Cancel confirmation
     */
    public function cancelConfirmation(): void
    {
        $this->showConfirmModal = false;
        $this->confirmAction = '';
        $this->confirmMessage = '';
    }

    /**
     * Add message to update log
     */
    protected function addToLog(string $message): void
    {
        $this->updateLog[] = [
            'timestamp' => now()->format('H:i:s'),
            'message' => $message
        ];

        Log::info('Update process: ' . $message);
    }

    /**
     * Delete directory recursively
     */
    protected function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }

    /**
     * Set active tab and perform auto-checks
     */
    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;
        
        // Auto-check system requirements when switching to requirements tab
        if ($tab === 'requirements' && (empty($this->systemRequirements) || !$this->requirementsMet)) {
            $this->checkSystemRequirements();
        }
        
        // Auto-check for updates when switching to updates tab if repository is configured
        if ($tab === 'updates' && !empty($this->repositoryUrl) && empty($this->latestVersion)) {
            $this->checkForUpdates();
        }
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.admin.system-updates')
            ->layout('layouts.admin');
    }

    /**
     * Finalize update process
     */
    protected function finalizeUpdate(): void
    {
        // Clear caches
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        
        // Update version file if it exists
        $versionFile = base_path('version.txt');
        if (file_exists($versionFile)) {
            file_put_contents($versionFile, $this->latestVersion);
        }
        
        Log::info('Update finalization completed');
    }
}
