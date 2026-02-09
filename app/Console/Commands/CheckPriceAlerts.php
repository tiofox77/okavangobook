<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckPriceAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-price-alerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Verificando alertas de preço...');
        
        $alerts = \App\Models\PriceAlert::pending()->get();
        
        $triggered = 0;
        
        foreach ($alerts as $alert) {
            if ($alert->checkPrice()) {
                $triggered++;
                $this->info("Alerta #{$alert->id} ativado para usuário #{$alert->user_id}");
            }
        }
        
        $this->info("Total de alertas verificados: {$alerts->count()}");
        $this->info("Alertas ativados: {$triggered}");
        
        return 0;
    }
}
