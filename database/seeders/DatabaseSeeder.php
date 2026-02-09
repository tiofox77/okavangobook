<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        // Executar os seeders na ordem correta para manter a integridade dos dados
        $this->call([
            // Primeiro os roles e permissões (necessários para o sistema de autenticação)
            RoleSeeder::class,
            
            // Criar usuários do sistema (após roles)
            UserSeeder::class,
            
            // Comodidades (independentes de outros dados)
            AmenitySeeder::class,
            
            // Primeiro localizações (precisamos ter localizações antes de cadastrar hotéis)
            LocationSeeder::class,
            
            // Depois hotéis (precisamos ter hotéis antes de cadastrar tipos de quartos)
            HotelSeeder::class,
            
            // Depois tipos de quartos (precisamos ter tipos de quartos antes de cadastrar preços)
            RoomTypeSeeder::class,
            
            // Por último os preços
            PriceSeeder::class,
        ]);
    }
}
