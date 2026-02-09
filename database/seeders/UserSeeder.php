<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Hotel;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔐 Criando usuários do sistema...');
        
        // 1. Criar Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@kiandastay.vip'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('Admin2017'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('Admin');
        $this->command->info('✅ Super Admin criado: admin@kiandastay.vip');
        
        // 2. Manter usuário Softecangola como Admin
        $softec = User::where('email', 'softecangola@gmail.com')->first();
        if ($softec && !$softec->hasRole('Admin')) {
            $softec->assignRole('Admin');
            $this->command->info('✅ Admin Softecangola mantido');
        }
        
        // 3. Criar usuários para cada hotel
        $hotels = Hotel::whereNull('user_id')->get();
        
        $this->command->info("🏨 Criando usuários para {$hotels->count()} hotéis...");
        
        $bar = $this->command->getOutput()->createProgressBar($hotels->count());
        $bar->start();
        
        foreach ($hotels as $hotel) {
            // Gerar email baseado no nome do hotel
            $hotelSlug = Str::slug($hotel->name);
            $email = $hotelSlug . '@kiandastay.vip';
            
            // Verificar se já existe
            $existingUser = User::where('email', $email)->first();
            
            if (!$existingUser) {
                // Criar novo usuário
                $user = User::create([
                    'name' => $hotel->name . ' - Manager',
                    'email' => $email,
                    'password' => bcrypt('Hotel2017'), // Senha padrão para todos os hotéis
                    'email_verified_at' => now(),
                ]);
                
                // Atribuir role Propriedade (gestor de propriedade)
                $user->assignRole('Propriedade');
                
                // Vincular ao hotel
                $hotel->user_id = $user->id;
                $hotel->save();
                
                $bar->advance();
            } else {
                // Vincular usuário existente ao hotel
                $hotel->user_id = $existingUser->id;
                $hotel->save();
                $bar->advance();
            }
        }
        
        $bar->finish();
        $this->command->newLine();
        
        // Estatísticas finais
        $totalUsers = User::count();
        $totalAdmins = User::role('Admin')->count();
        $hotelsWithUsers = Hotel::whereNotNull('user_id')->count();
        
        $this->command->newLine();
        $this->command->info('📊 Estatísticas:');
        $this->command->info("   Total de usuários: {$totalUsers}");
        $this->command->info("   Administradores: {$totalAdmins}");
        $this->command->info("   Hotéis com usuários: {$hotelsWithUsers}");
        $this->command->newLine();
        $this->command->info('✅ UserSeeder concluído com sucesso!');
    }
}
