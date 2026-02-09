<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Verificar alertas de preço a cada hora
        $schedule->command('alerts:check-prices')->hourly();
        
        // Verificar atualizações do sistema duas vezes por dia
        $schedule->command('system:check-updates')->twiceDaily(8, 20);
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
