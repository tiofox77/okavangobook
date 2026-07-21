<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminSmokeTest extends TestCase
{
    use DatabaseTransactions;

    private function adminUser(): User
    {
        foreach (['Admin', 'Propriedade', 'User'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $user = User::create([
            'name' => 'Admin Teste',
            'email' => 'admin-teste-' . uniqid() . '@exemplo.com',
            'password' => bcrypt('secret123'),
        ]);
        $user->assignRole('Admin');

        return $user;
    }

    /**
     * As páginas principais do admin devem renderizar (não 500) para um Admin.
     */
    public function test_admin_pages_do_not_error(): void
    {
        $admin = $this->adminUser();

        $routes = [
            'admin.dashboard',
            'admin.hotels',
            'admin.rooms',
            'admin.reservations',
            'admin.users',
            'admin.locations',
            'admin.coupons',
            'admin.newsletter',
            'admin.analytics',
            'admin.articles',
            'admin.settings',
            'admin.plans',
            'admin.payments',
        ];

        foreach ($routes as $name) {
            $response = $this->actingAs($admin)->get(route($name));
            $this->assertNotEquals(500, $response->status(), "A rota {$name} devolveu 500.");
            $this->assertContains($response->status(), [200, 302], "A rota {$name} devolveu {$response->status()}.");
        }
    }

    public function test_admin_area_is_protected_from_guests(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect();
    }

    public function test_normal_user_cannot_access_admin(): void
    {
        foreach (['Admin', 'Propriedade', 'User'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
        $user = User::create([
            'name' => 'User',
            'email' => 'user-' . uniqid() . '@exemplo.com',
            'password' => bcrypt('secret123'),
        ]);
        $user->assignRole('User');

        $this->actingAs($user)->get(route('admin.dashboard'))->assertStatus(403);
    }
}
