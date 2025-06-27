<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

class SettingsManagement extends Component
{
    use WithFileUploads;

    // General Settings
    public string $appName = '';
    public string $appDescription = '';
    public string $appKeywords = '';
    public string $appCurrency = 'KZ';
    public string $appTimezone = 'Africa/Luanda';
    public string $appLanguage = 'pt';
    public $appLogo;
    public $appFavicon;
    public $heroBackground;
    
    // Company Details
    public string $contactEmail = '';
    public string $contactPhone = '';
    public string $contactAddress = '';
    
    // Social Media
    public string $socialFacebook = '';
    public string $socialInstagram = '';
    public string $socialTwitter = '';

    // System Requirements
    public array $systemRequirements = [];
    public bool $isCheckingRequirements = false;
    public array $requirementsStatus = [
        'passed' => 0,
        'warnings' => 0,
        'failed' => 0
    ];

    // Maintenance & Debug Settings
    public bool $maintenanceMode = false;
    public bool $debugMode = false;

    // Modal states
    public bool $showConfirmModal = false;
    public string $confirmAction = '';
    public string $confirmMessage = '';
    public mixed $confirmData = null;

    // Active tab
    #[Url(history: true)]
    public string $activeTab = 'general';

    /**
     * Define validation rules
     */
    protected function rules(): array
    {
        return [
            'appName' => 'required|string|max:255',
            'appDescription' => 'nullable|string|max:500',
            'appKeywords' => 'nullable|string|max:255',
            'appCurrency' => 'required|string|size:2',
            'appTimezone' => 'required|string',
            'appLanguage' => 'required|string|size:2',
            'appLogo' => $this->appLogo instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile
                ? 'image|max:1024'
                : 'nullable',
            'appFavicon' => $this->appFavicon instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile
                ? 'image|max:512'
                : 'nullable',
            'heroBackground' => $this->heroBackground instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile
                ? 'image|max:2048'
                : 'nullable',
            'contactEmail' => 'nullable|email|max:100',
            'contactPhone' => 'nullable|string|max:30',
            'contactAddress' => 'nullable|string|max:255',
            'socialFacebook' => 'nullable|string|max:100',
            'socialInstagram' => 'nullable|string|max:100',
            'socialTwitter' => 'nullable|string|max:100',
        ];
    }

    /**
     * Initialize component data
     */
    public function mount(): void
    {
        $this->loadSettings();

        // Auto check system requirements if that tab is active
        if ($this->activeTab === 'requirements') {
            $this->checkSystemRequirements();
        }
    }

    /**
     * Load settings from database
     */
    protected function loadSettings(): void
    {
        $this->appName = Setting::get('app_name', config('app.name', 'OkavangoBook'));
        $this->appDescription = Setting::get('app_description', 'Sistema de reservas de hotéis em Angola');
        $this->appKeywords = Setting::get('app_keywords', 'hotéis, reservas, Angola, turismo');
        $this->appCurrency = Setting::get('app_currency', 'KZ');
        $this->appTimezone = Setting::get('app_timezone', 'Africa/Luanda');
        $this->appLanguage = Setting::get('app_language', 'pt');
        
        // Contact Details
        $this->contactEmail = Setting::get('contact_email', '');
        $this->contactPhone = Setting::get('contact_phone', '');
        $this->contactAddress = Setting::get('contact_address', '');
        
        // Social Media
        $this->socialFacebook = Setting::get('social_facebook', '');
        $this->socialInstagram = Setting::get('social_instagram', '');
        $this->socialTwitter = Setting::get('social_twitter', '');
        
        // System Settings
        $this->maintenanceMode = (bool) Setting::get('maintenance_mode', false);
        $this->debugMode = (bool) Setting::get('debug_mode', false);
    }

    /**
     * Real-time validation on property updates
     */
    public function updated(string $propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    /**
     * Change the active tab
     */
    public function setActiveTab(string $tab): void
    {
        $this->activeTab = $tab;

        // Auto check system requirements when switching to that tab
        if ($tab === 'requirements' && empty($this->systemRequirements)) {
            $this->checkSystemRequirements();
        }
    }

    /**
     * Save general settings
     */
    public function saveGeneralSettings(): void
    {
        $this->validate([
            'appName' => 'required|string|max:255',
            'appDescription' => 'nullable|string|max:500',
            'appKeywords' => 'nullable|string|max:255',
            'appCurrency' => 'required|string|size:2',
            'appTimezone' => 'required|string',
            'appLanguage' => 'required|string|size:2',
            'appLogo' => $this->appLogo instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile
                ? 'image|max:1024'
                : 'nullable',
            'appFavicon' => $this->appFavicon instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile
                ? 'image|max:512'
                : 'nullable',
            'heroBackground' => $this->heroBackground instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile
                ? 'image|max:2048'
                : 'nullable',
        ]);

        try {
            Log::info('Saving general settings', [
                'app_name' => $this->appName,
                'app_description' => $this->appDescription,
                'app_currency' => $this->appCurrency,
                'app_timezone' => $this->appTimezone,
                'app_language' => $this->appLanguage,
            ]);

            // Handle file uploads
            if ($this->appLogo instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                $logoPath = $this->appLogo->store('assets', 'public');
                Setting::set('app_logo', $logoPath, 'general', 'string', 'Logo da aplicação', true);
                Log::info('App logo saved at: ' . $logoPath);
            }

            if ($this->appFavicon instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                $faviconPath = $this->appFavicon->store('assets', 'public');
                Setting::set('app_favicon', $faviconPath, 'general', 'string', 'Favicon da aplicação', true);
                Log::info('App favicon saved at: ' . $faviconPath);
            }

            if ($this->heroBackground instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                $heroBgPath = $this->heroBackground->store('assets', 'public');
                Setting::set('hero_background', $heroBgPath, 'general', 'string', 'Imagem de fundo hero', true);
                Log::info('Hero background saved at: ' . $heroBgPath);
            }

            // Save other settings
            Setting::set('app_name', $this->appName, 'general', 'string', 'Nome da aplicação', true);
            Setting::set('app_description', $this->appDescription, 'general', 'string', 'Descrição da aplicação', true);
            Setting::set('app_keywords', $this->appKeywords, 'general', 'string', 'Palavras-chave da aplicação', true);
            Setting::set('app_currency', $this->appCurrency, 'general', 'string', 'Moeda da aplicação', true);
            Setting::set('app_timezone', $this->appTimezone, 'general', 'string', 'Fuso horário da aplicação', true);
            Setting::set('app_language', $this->appLanguage, 'general', 'string', 'Idioma da aplicação', true);

            // Update .env file
            $this->updateEnvFile([
                'APP_NAME' => $this->appName,
                'APP_DESCRIPTION' => $this->appDescription,
                'APP_KEYWORDS' => $this->appKeywords,
                'APP_CURRENCY' => $this->appCurrency,
                'APP_LOCALE' => $this->appLanguage,
            ]);

            // Clear cache to apply new settings
            $this->clearCache();

            session()->flash('success', 'Configurações gerais salvas com sucesso!');
        } catch (\Exception $e) {
            Log::error('Error saving general settings: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            session()->flash('error', 'Erro ao salvar configurações: ' . $e->getMessage());
        }
    }

    /**
     * Save contact settings
     */
    public function saveContactSettings(): void
    {
        $this->validate([
            'contactEmail' => 'nullable|email|max:100',
            'contactPhone' => 'nullable|string|max:30',
            'contactAddress' => 'nullable|string|max:255',
            'socialFacebook' => 'nullable|string|max:100',
            'socialInstagram' => 'nullable|string|max:100',
            'socialTwitter' => 'nullable|string|max:100',
        ]);

        try {
            // Save contact details
            Setting::set('contact_email', $this->contactEmail, 'contact', 'string', 'Email de contacto', true);
            Setting::set('contact_phone', $this->contactPhone, 'contact', 'string', 'Telefone de contacto', true);
            Setting::set('contact_address', $this->contactAddress, 'contact', 'string', 'Endereço de contacto', true);
            
            // Save social media
            Setting::set('social_facebook', $this->socialFacebook, 'social', 'string', 'Facebook URL', true);
            Setting::set('social_instagram', $this->socialInstagram, 'social', 'string', 'Instagram URL', true);
            Setting::set('social_twitter', $this->socialTwitter, 'social', 'string', 'Twitter URL', true);

            // Update .env file
            $this->updateEnvFile([
                'APP_CONTACT_EMAIL' => $this->contactEmail,
                'APP_CONTACT_PHONE' => $this->contactPhone,
                'APP_CONTACT_ADDRESS' => $this->contactAddress,
                'APP_SOCIAL_FACEBOOK' => $this->socialFacebook,
                'APP_SOCIAL_INSTAGRAM' => $this->socialInstagram,
                'APP_SOCIAL_TWITTER' => $this->socialTwitter,
            ]);

            session()->flash('success', 'Configurações de contacto salvas com sucesso!');
        } catch (\Exception $e) {
            Log::error('Error saving contact settings: ' . $e->getMessage());
            session()->flash('error', 'Erro ao salvar configurações de contacto: ' . $e->getMessage());
        }
    }

    /**
     * Save maintenance settings
     */
    public function saveMaintenanceSettings(): void
    {
        try {
            Log::info('Saving maintenance settings', [
                'maintenance_mode' => $this->maintenanceMode,
                'debug_mode' => $this->debugMode,
            ]);

            Setting::set('maintenance_mode', $this->maintenanceMode ? '1' : '0', 'maintenance', 'boolean', 'Modo de manutenção', true);
            Setting::set('debug_mode', $this->debugMode ? '1' : '0', 'maintenance', 'boolean', 'Modo debug', false);

            // Apply maintenance mode
            if ($this->maintenanceMode) {
                Artisan::call('down');
                Log::info('Maintenance mode activated');
            } else {
                Artisan::call('up');
                Log::info('Maintenance mode deactivated');
            }

            $this->clearCache();

            session()->flash('success', 'Configurações de manutenção salvas com sucesso!');
        } catch (\Exception $e) {
            Log::error('Error saving maintenance settings: ' . $e->getMessage());
            session()->flash('error', 'Erro ao salvar configurações de manutenção: ' . $e->getMessage());
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
            // PHP Version
            $phpVersion = PHP_VERSION;
            $minPhpVersion = '8.1.0';
            $phpStatus = version_compare($phpVersion, $minPhpVersion, '>=') ? 'passed' : 'failed';
            $this->addRequirement('PHP Version', $phpVersion, "PHP >= {$minPhpVersion} required", $phpStatus);

            // Laravel Version
            $laravelVersion = app()->version();
            $this->addRequirement('Laravel Version', $laravelVersion, 'Framework version', 'passed');

            // Extensions
            $extensions = [
                'openssl' => 'OpenSSL PHP Extension',
                'pdo' => 'PDO PHP Extension',
                'mbstring' => 'Mbstring PHP Extension',
                'tokenizer' => 'Tokenizer PHP Extension',
                'xml' => 'XML PHP Extension',
                'ctype' => 'Ctype PHP Extension',
                'json' => 'JSON PHP Extension',
                'bcmath' => 'BCMath PHP Extension',
                'curl' => 'cURL PHP Extension',
                'zip' => 'ZIP PHP Extension',
                'gd' => 'GD PHP Extension',
            ];

            foreach ($extensions as $extension => $name) {
                $status = extension_loaded($extension) ? 'passed' : 'failed';
                $this->addRequirement($name, extension_loaded($extension) ? 'Loaded' : 'Not Found', 'Required for application', $status);
            }

            // Permissions
            $directories = [
                'storage/app' => storage_path('app'),
                'storage/framework' => storage_path('framework'),
                'storage/logs' => storage_path('logs'),
                'bootstrap/cache' => base_path('bootstrap/cache'),
            ];

            foreach ($directories as $name => $path) {
                $writable = is_writable($path);
                $status = $writable ? 'passed' : 'failed';
                $this->addRequirement("$name Directory", $writable ? 'Writable' : 'Not Writable', 'Write permission required', $status);
            }

            // Database Connection
            try {
                DB::connection()->getPdo();
                $this->addRequirement('Database Connection', 'Connected', 'Database connectivity', 'passed');
            } catch (\Exception $e) {
                $this->addRequirement('Database Connection', 'Failed', $e->getMessage(), 'failed');
            }

            // Memory Limit
            $memoryLimit = ini_get('memory_limit');
            $memoryBytes = $this->convertToBytes($memoryLimit);
            $minMemory = $this->convertToBytes('128M');
            $status = $memoryBytes >= $minMemory ? 'passed' : 'warning';
            $this->addRequirement('Memory Limit', $memoryLimit, 'Minimum 128M recommended', $status);

            // Max Execution Time
            $maxExecutionTime = ini_get('max_execution_time');
            $status = $maxExecutionTime >= 30 ? 'passed' : 'warning';
            $this->addRequirement('Max Execution Time', $maxExecutionTime . 's', 'Minimum 30s recommended', $status);

        } catch (\Exception $e) {
            Log::error('Error checking system requirements: ' . $e->getMessage());
        }

        $this->isCheckingRequirements = false;
    }

    /**
     * Add a requirement to the list
     */
    protected function addRequirement(string $name, string $current, string $required, string $status): void
    {
        $this->systemRequirements[] = [
            'name' => $name,
            'current' => $current,
            'required' => $required,
            'status' => $status,
        ];

        $this->requirementsStatus[$status]++;
    }

    /**
     * Convert memory limit to bytes
     */
    protected function convertToBytes(string $value): int
    {
        $value = trim($value);
        $last = strtolower($value[strlen($value) - 1]);
        $value = (int) $value;

        switch ($last) {
            case 'g':
                $value *= 1024;
            case 'm':
                $value *= 1024;
            case 'k':
                $value *= 1024;
        }

        return $value;
    }

    /**
     * Clear application cache
     */
    public function clearCache(): void
    {
        try {
            // Clear various caches
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            
            // Clear settings cache
            Setting::clearCache();

            Log::info('All caches cleared successfully');
            session()->flash('success', 'Cache limpo com sucesso!');
        } catch (\Exception $e) {
            Log::error('Error clearing cache: ' . $e->getMessage());
            session()->flash('error', 'Erro ao limpar cache: ' . $e->getMessage());
        }
    }

    /**
     * Show confirmation modal
     */
    public function showConfirmation(string $action, string $message, mixed $data = null): void
    {
        $this->confirmAction = $action;
        $this->confirmMessage = $message;
        $this->confirmData = $data;
        $this->showConfirmModal = true;
    }

    /**
     * Execute confirmed action
     */
    public function executeConfirmedAction(): void
    {
        $this->showConfirmModal = false;

        switch ($this->confirmAction) {
            case 'clearCache':
                $this->clearCache();
                break;
            case 'enableMaintenance':
                $this->maintenanceMode = true;
                $this->saveMaintenanceSettings();
                break;
            case 'disableMaintenance':
                $this->maintenanceMode = false;
                $this->saveMaintenanceSettings();
                break;
        }

        $this->confirmAction = '';
        $this->confirmMessage = '';
        $this->confirmData = null;
    }

    /**
     * Cancel confirmation
     */
    public function cancelConfirmation(): void
    {
        $this->showConfirmModal = false;
        $this->confirmAction = '';
        $this->confirmMessage = '';
        $this->confirmData = null;
    }

    /**
     * Update .env file
     */
    protected function updateEnvFile(array $data): void
    {
        try {
            $envFile = base_path('.env');
            $envContent = file_get_contents($envFile);
            
            foreach ($data as $key => $value) {
                $pattern = "/^{$key}=.*/m";
                $replacement = "{$key}=\"{$value}\"";
                
                if (preg_match($pattern, $envContent)) {
                    $envContent = preg_replace($pattern, $replacement, $envContent);
                } else {
                    $envContent .= "\n{$replacement}";
                }
            }
            
            file_put_contents($envFile, $envContent);
            Log::info('Environment file updated', ['keys' => array_keys($data)]);
        } catch (\Exception $e) {
            Log::error('Failed to update .env file: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.admin.settings-management')
            ->layout('layouts.admin');
    }
}
