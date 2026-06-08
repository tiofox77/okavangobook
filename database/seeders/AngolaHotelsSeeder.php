<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\User;
use Database\Seeders\Angola\HotelsLuandaSeeder;
use Database\Seeders\Angola\HotelsBenguelaSeeder;
use Database\Seeders\Angola\HotelsLubangoSeeder;
use Database\Seeders\Angola\HotelsNamibeSeeder;
use Database\Seeders\Angola\HotelsHuamboSeeder;
use Database\Seeders\Angola\HotelsMalanjeSeeder;
use Database\Seeders\Angola\HotelsCabindaSeeder;
use Database\Seeders\Angola\HotelsNorteSeeder;
use Database\Seeders\Angola\HotelsLesteSeeder;
use Database\Seeders\Angola\HotelsSulSeeder;
use Database\Seeders\Angola\HotelsRealBenguelaSeeder;
use Database\Seeders\Angola\HotelsRealLubangoSeeder;
use Database\Seeders\Angola\HotelsRealNamibeSeeder;
use Database\Seeders\Angola\HotelsRealHuamboSeeder;
use Database\Seeders\Angola\HotelsRealCabindaMalanjeSeeder;
use Illuminate\Database\Seeder;

/**
 * AngolaHotelsSeeder — Orquestrador principal
 *
 * Cria hotéis, resorts e hospedarias reais de Angola por província.
 * Cobre todas as 18 províncias angolanas com ~80 propriedades no total.
 *
 * Execute: php artisan db:seed --class=AngolaHotelsSeeder
 */
class AngolaHotelsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::whereHas('roles', fn($q) => $q->where('name', 'Admin'))->first();

        if (!$admin) {
            $this->command->error('❌ Nenhum Admin encontrado. Execute o UserSeeder primeiro.');
            return;
        }

        $before = Hotel::where('is_active', true)->count();
        $this->command->info("🏨 A criar hotéis de Angola por província...");
        $this->command->info("   Propriedades activas antes: {$before}");

        // ── Luanda (11 propriedades) ──────────────────────────────────
        $this->command->line('  → Luanda...');
        (new HotelsLuandaSeeder())->run($admin);

        // ── Benguela + Lobito (9 propriedades) ───────────────────────
        $this->command->line('  → Benguela & Lobito...');
        (new HotelsBenguelaSeeder())->run($admin);

        // ── Lubango / Huíla (6 propriedades) ─────────────────────────
        $this->command->line('  → Lubango (Huíla)...');
        (new HotelsLubangoSeeder())->run($admin);

        // ── Namibe (5 propriedades) ───────────────────────────────────
        $this->command->line('  → Namibe...');
        (new HotelsNamibeSeeder())->run($admin);

        // ── Huambo (5 propriedades) ───────────────────────────────────
        $this->command->line('  → Huambo...');
        (new HotelsHuamboSeeder())->run($admin);

        // ── Malanje (4 propriedades) ──────────────────────────────────
        $this->command->line('  → Malanje...');
        (new HotelsMalanjeSeeder())->run($admin);

        // ── Cabinda (3 propriedades) ──────────────────────────────────
        $this->command->line('  → Cabinda...');
        (new HotelsCabindaSeeder())->run($admin);

        // ── Norte: Soyo, Uíge, N'dalatando, Caxito (8 propriedades) ─
        $this->command->line('  → Norte (Soyo, Uíge, Cuanza Norte, Bengo)...');
        (new HotelsNorteSeeder())->run($admin);

        // ── Leste: Dundo, Saurimo, Kuito, Luena, Sumbe (10 props.) ──
        $this->command->line('  → Leste & Centro (Lundas, Bié, Moxico, Cuanza Sul)...');
        (new HotelsLesteSeeder())->run($admin);

        // ── Sul: Ondjiva, Menongue (6 propriedades) ───────────────────
        $this->command->line('  → Sul (Cunene, Cuando Cubango)...');
        (new HotelsSulSeeder())->run($admin);

        // ── REAL DATA — fonte: hoteisangola.com ───────────────────────
        $this->command->line('  → Benguela/Lobito/Catumbela (reais hoteisangola.com)...');
        (new HotelsRealBenguelaSeeder())->run($admin);

        $this->command->line('  → Lubango — reais (Pululukwa, Chik Chik, Serra da Chela, Kimbo do Soba, VIP, Mask, Freitas, Vila Mara)...');
        (new HotelsRealLubangoSeeder())->run($admin);

        $this->command->line('  → Namibe — reais (Chik Chik, Viva Executive, Baía das Pipas, Mariquita, Praia do Soba)...');
        (new HotelsRealNamibeSeeder())->run($admin);

        $this->command->line('  → Huambo — reais (Engrácia, Roma Ritz, Paraíso Chiva, IU Hotel, Tropicana, Catito Bailundo)...');
        (new HotelsRealHuamboSeeder())->run($admin);

        $this->command->line('  → Cabinda + Soyo + Malanje/Kalandula — reais...');
        (new HotelsRealCabindaMalanjeSeeder())->run($admin);

        $after  = Hotel::where('is_active', true)->count();
        $created = $after - $before;

        $this->command->newLine();
        $this->command->info("✅ Concluído!");
        $this->command->info("   Propriedades criadas: {$created}");
        $this->command->info("   Total activas na BD:  {$after}");
    }
}
