<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use ZipArchive;

class UpdatesManagement extends Component
{
    /**
     * Informações do sistema
     */
    public string $currentVersion = '1.0.0';
    public string $latestVersion = '';
    public string $updateStatus = 'checking'; // checking, available, not_available, downloading, installing, completed, error
    public string $updateMessage = '';
    public array $updateLog = [];
    public array $releaseNotes = [];
    
    /**
     * Configurações do GitHub
     */
    public string $githubRepo = 'username/okavangobook'; // Alterar para o seu repositório
    public string $githubToken = ''; // Token do GitHub (opcional)
    
    /**
     * Estados da interface
     */
    public bool $showReleaseNotes = false;
    public bool $confirmUpdate = false;
    public int $downloadProgress = 0;
    public bool $showGitHubSettings = false;
    
    /**
     * Inicialização do componente
     */
    public function mount(): void
    {
        $this->currentVersion = config('app.version', '1.0.0');
        $this->githubRepo = config('app.github_repo', 'username/okavangobook');
        $this->githubToken = config('app.github_token', '');
        $this->checkForUpdates();
    }
    
    /**
     * Verificar actualizações disponíveis
     */
    public function checkForUpdates(): void
    {
        $this->updateStatus = 'checking';
        $this->updateMessage = 'A verificar actualizações...';
        
        try {
            // Fazer request para API do GitHub
            $response = Http::timeout(30)->get("https://api.github.com/repos/{$this->githubRepo}/releases/latest");
            
            if ($response->successful()) {
                $release = $response->json();
                $this->latestVersion = ltrim($release['tag_name'], 'v');
                $this->releaseNotes = [
                    'name' => $release['name'],
                    'body' => $release['body'],
                    'published_at' => $release['published_at'],
                    'download_url' => $release['zipball_url'],
                ];
                
                // Comparar versões
                if (version_compare($this->latestVersion, $this->currentVersion, '>')) {
                    $this->updateStatus = 'available';
                    $this->updateMessage = "Nova versão disponível: v{$this->latestVersion}";
                } else {
                    $this->updateStatus = 'not_available';
                    $this->updateMessage = 'Sistema actualizado para a versão mais recente.';
                }
            } else {
                throw new \Exception('Falha na comunicação com o GitHub');
            }
            
        } catch (\Exception $e) {
            $this->updateStatus = 'error';
            $this->updateMessage = 'Erro ao verificar actualizações: ' . $e->getMessage();
            $this->addToLog('Erro: ' . $e->getMessage());
        }
    }
    
    /**
     * Mostrar/esconder notas da versão
     */
    public function toggleReleaseNotes(): void
    {
        $this->showReleaseNotes = !$this->showReleaseNotes;
    }
    
    /**
     * Confirmar actualização
     */
    public function confirmUpdateProcess(): void
    {
        $this->confirmUpdate = true;
    }
    
    /**
     * Cancelar actualização
     */
    public function cancelUpdate(): void
    {
        $this->confirmUpdate = false;
    }
    
    /**
     * Iniciar processo de actualização
     */
    public function startUpdate(): void
    {
        if (!$this->confirmUpdate) {
            session()->flash('error', 'Por favor, confirme a actualização primeiro.');
            return;
        }
        
        try {
            $this->updateStatus = 'downloading';
            $this->updateMessage = 'A descarregar actualização...';
            $this->addToLog('Iniciando processo de actualização');
            
            // 1. Fazer backup do sistema atual
            $this->createBackup();
            
            // 2. Descarregar nova versão
            $zipPath = $this->downloadUpdate();
            
            // 3. Extrair e instalar
            $this->installUpdate($zipPath);
            
            // 4. Limpar arquivos temporários
            $this->cleanup($zipPath);
            
            $this->updateStatus = 'completed';
            $this->updateMessage = 'Actualização concluída com sucesso!';
            $this->addToLog('Actualização concluída com sucesso');
            
            // Actualizar versão atual
            $this->currentVersion = $this->latestVersion;
            
        } catch (\Exception $e) {
            $this->updateStatus = 'error';
            $this->updateMessage = 'Erro durante a actualização: ' . $e->getMessage();
            $this->addToLog('Erro: ' . $e->getMessage());
        }
    }
    
    /**
     * Criar backup do sistema
     */
    protected function createBackup(): void
    {
        $this->addToLog('Criando backup do sistema...');
        
        $backupPath = storage_path('app/backups/');
        
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }
        
        $backupName = 'backup_' . date('Y-m-d_H-i-s') . '.zip';
        $zip = new ZipArchive();
        
        if ($zip->open($backupPath . $backupName, ZipArchive::CREATE) === TRUE) {
            $this->addFilesToZip($zip, base_path(), '');
            $zip->close();
            $this->addToLog("Backup criado: {$backupName}");
        } else {
            throw new \Exception('Falha ao criar backup');
        }
    }
    
    /**
     * Descarregar actualização
     */
    protected function downloadUpdate(): string
    {
        $this->addToLog('Descarregando nova versão...');
        
        $downloadUrl = $this->releaseNotes['download_url'];
        $tempPath = storage_path('app/temp/');
        
        if (!File::exists($tempPath)) {
            File::makeDirectory($tempPath, 0755, true);
        }
        
        $zipPath = $tempPath . 'update.zip';
        
        // Simular progresso de download
        for ($i = 0; $i <= 100; $i += 10) {
            $this->downloadProgress = $i;
            sleep(1); // Simular tempo de download
        }
        
        // Fazer download real (simplificado)
        $response = Http::timeout(300)->get($downloadUrl);
        
        if ($response->successful()) {
            File::put($zipPath, $response->body());
            $this->addToLog('Download concluído');
            return $zipPath;
        } else {
            throw new \Exception('Falha no download da actualização');
        }
    }
    
    /**
     * Instalar actualização
     */
    protected function installUpdate(string $zipPath): void
    {
        $this->updateStatus = 'installing';
        $this->updateMessage = 'A instalar actualização...';
        $this->addToLog('Instalando actualização...');
        
        $extractPath = storage_path('app/temp/extract/');
        
        if (!File::exists($extractPath)) {
            File::makeDirectory($extractPath, 0755, true);
        }
        
        $zip = new ZipArchive();
        
        if ($zip->open($zipPath) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();
            
            // Copiar arquivos (excluindo .env e storage)
            $this->copyUpdateFiles($extractPath, base_path());
            $this->addToLog('Arquivos copiados com sucesso');
            
            // Executar comandos pós-instalação
            $this->runPostInstallCommands();
            
        } else {
            throw new \Exception('Falha ao extrair arquivo de actualização');
        }
    }
    
    /**
     * Executar comandos pós-instalação
     */
    protected function runPostInstallCommands(): void
    {
        $this->addToLog('Executando comandos pós-instalação...');
        
        try {
            // Limpar cache
            \Artisan::call('cache:clear');
            \Artisan::call('config:cache');
            \Artisan::call('route:cache');
            \Artisan::call('view:cache');
            
            // Executar migrações se existirem
            \Artisan::call('migrate', ['--force' => true]);
            
            $this->addToLog('Comandos executados com sucesso');
            
        } catch (\Exception $e) {
            $this->addToLog('Aviso: Alguns comandos falharam - ' . $e->getMessage());
        }
    }
    
    /**
     * Copiar arquivos da actualização
     */
    protected function copyUpdateFiles(string $source, string $destination): void
    {
        $excludePatterns = ['.env', 'storage', 'node_modules', '.git'];
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $item) {
            $shouldExclude = false;
            
            foreach ($excludePatterns as $pattern) {
                if (strpos($item->getPathname(), $pattern) !== false) {
                    $shouldExclude = true;
                    break;
                }
            }
            
            if (!$shouldExclude) {
                $target = $destination . '/' . substr($item, strlen($source));
                
                if ($item->isDir()) {
                    if (!File::exists($target)) {
                        File::makeDirectory($target, 0755, true);
                    }
                } else {
                    File::copy($item, $target);
                }
            }
        }
    }
    
    /**
     * Adicionar arquivos ao ZIP
     */
    protected function addFilesToZip(ZipArchive $zip, string $source, string $relativePath): void
    {
        $excludePatterns = ['storage/app', 'storage/logs', 'node_modules', '.git'];
        
        $files = File::allFiles($source);
        
        foreach ($files as $file) {
            $shouldExclude = false;
            
            foreach ($excludePatterns as $pattern) {
                if (strpos($file->getPathname(), $pattern) !== false) {
                    $shouldExclude = true;
                    break;
                }
            }
            
            if (!$shouldExclude) {
                $zip->addFile($file->getPathname(), $relativePath . $file->getRelativePathname());
            }
        }
    }
    
    /**
     * Limpar arquivos temporários
     */
    protected function cleanup(string $zipPath): void
    {
        $this->addToLog('Limpando arquivos temporários...');
        
        if (File::exists($zipPath)) {
            File::delete($zipPath);
        }
        
        $extractPath = storage_path('app/temp/extract/');
        if (File::exists($extractPath)) {
            File::deleteDirectory($extractPath);
        }
        
        $this->addToLog('Limpeza concluída');
    }
    
    /**
     * Adicionar entrada ao log
     */
    protected function addToLog(string $message): void
    {
        $this->updateLog[] = [
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'message' => $message,
        ];
    }
    
    /**
     * Limpar log de actualização
     */
    public function clearUpdateLog(): void
    {
        $this->updateLog = [];
    }
    
    /**
     * Render do componente
     */
    public function render(): View
    {
        return view('livewire.admin.updates-management')
            ->layout('layouts.admin');
    }
    
    /**
     * Salvar configurações do GitHub
     */
    public function saveGitHubSettings(): void
    {
        $this->validate([
            'githubRepo' => 'required|string|max:255',
            'githubToken' => 'nullable|string|max:255',
        ]);

        try {
            // Atualizar configurações no arquivo .env
            $this->updateEnvFile([
                'APP_GITHUB_REPO' => $this->githubRepo,
                'APP_GITHUB_TOKEN' => $this->githubToken,
            ]);
            
            $this->addToLog('Configurações do GitHub atualizadas');
            session()->flash('success', 'Configurações do GitHub salvas com sucesso!');
            $this->showGitHubSettings = false;
            
        } catch (\Exception $e) {
            \Log::error('Erro ao salvar configurações do GitHub: ' . $e->getMessage());
            session()->flash('error', 'Erro ao salvar configurações. Tente novamente.');
        }
    }
    
    /**
     * Mostrar/esconder configurações do GitHub
     */
    public function toggleGitHubSettings(): void
    {
        $this->showGitHubSettings = !$this->showGitHubSettings;
    }
    
    /**
     * Atualizar arquivo .env
     */
    protected function updateEnvFile(array $data): void
    {
        $envFile = base_path('.env');
        $envContent = file_get_contents($envFile);
        
        foreach ($data as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replacement = "{$key}={$value}";
            
            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
        }
        
        file_put_contents($envFile, $envContent);
    }
}
