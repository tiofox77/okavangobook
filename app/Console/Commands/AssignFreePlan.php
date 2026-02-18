<?php

namespace App\Console\Commands;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Console\Command;

class AssignFreePlan extends Command
{
    protected $signature = 'subscriptions:assign-free';
    protected $description = 'Assign free plan to all users who do not have an active subscription';

    public function handle(): int
    {
        $freePlan = Plan::where('is_free', true)->first();

        if (!$freePlan) {
            $this->error('Plano gratuito não encontrado. Execute o seeder primeiro: php artisan db:seed --class=PlanSeeder');
            return Command::FAILURE;
        }

        $usersWithoutSub = User::whereDoesntHave('subscriptions', function ($q) {
            $q->where('status', 'active')->where('ends_at', '>', now());
        })->get();

        $count = 0;
        foreach ($usersWithoutSub as $user) {
            $user->subscribeToPlan($freePlan);
            $count++;
            $this->line("  ✓ {$user->name} ({$user->email}) → Plano Gratuito");
        }

        $this->info("Concluído! {$count} utilizadores receberam o plano gratuito.");
        return Command::SUCCESS;
    }
}
