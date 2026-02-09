<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Criar roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $propriedadeRole = Role::firstOrCreate(['name' => 'Propriedade']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        // Criar permissões
        $permissions = [
            // Utilizador comum
            'make-booking',
            'view-own-bookings',
            'cancel-own-booking',
            'update-profile',
            
            // Gestor de Propriedade
            'manage-own-hotels',
            'manage-own-rooms',
            'manage-own-restaurant',
            'manage-own-leisure',
            'manage-own-bookings',
            'view-own-analytics',
            
            // Administrador
            'manage-users',
            'manage-hotels',
            'manage-locations',
            'manage-bookings',
            'manage-settings',
            'manage-system',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Atribuir permissões aos roles
        $userRole->givePermissionTo([
            'make-booking',
            'view-own-bookings',
            'cancel-own-booking',
            'update-profile',
        ]);

        $propriedadeRole->givePermissionTo([
            'make-booking',
            'view-own-bookings',
            'cancel-own-booking',
            'update-profile',
            'manage-own-hotels',
            'manage-own-rooms',
            'manage-own-restaurant',
            'manage-own-leisure',
            'manage-own-bookings',
            'view-own-analytics',
        ]);

        $adminRole->givePermissionTo(Permission::all());

        $this->command->info('Roles e permissões criados com sucesso!');
    }
}
