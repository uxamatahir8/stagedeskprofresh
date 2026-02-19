<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    use RefreshDatabase;

    public function test_has_role_returns_false_when_not_authenticated(): void
    {
        $this->assertFalse(hasRole('master_admin'));
        $this->assertFalse(hasRole('company_admin'));
    }

    public function test_has_role_returns_true_when_user_has_given_role(): void
    {
        $role = Role::create([
            'name' => 'Master Admin',
            'description' => 'Platform administrator',
            'role_key' => 'master_admin',
        ]);
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'company_id' => null,
        ]);

        $this->actingAs($user);

        $this->assertTrue(hasRole('master_admin'));
        $this->assertTrue(hasRole('master_admin', 'company_admin'));
        $this->assertFalse(hasRole('company_admin'));
    }

    public function test_is_master_admin_returns_true_only_for_master_admin(): void
    {
        $masterRole = Role::create([
            'name' => 'Master Admin',
            'description' => 'Admin',
            'role_key' => 'master_admin',
        ]);
        $customerRole = Role::create([
            'name' => 'Customer',
            'description' => 'Customer',
            'role_key' => 'customer',
        ]);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $masterRole->id,
            'company_id' => null,
        ]);
        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role_id' => $customerRole->id,
            'company_id' => null,
        ]);

        $this->actingAs($admin);
        $this->assertTrue(isMasterAdmin());

        $this->actingAs($customer);
        $this->assertFalse(isMasterAdmin());
    }

    public function test_get_current_user_company_id_returns_null_when_not_authenticated(): void
    {
        $this->assertNull(getCurrentUserCompanyId());
    }

    public function test_get_current_user_company_id_returns_company_id_when_authenticated(): void
    {
        $role = Role::create([
            'name' => 'Company Admin',
            'description' => 'Company admin',
            'role_key' => 'company_admin',
        ]);
        $user = User::create([
            'name' => 'Company Admin',
            'email' => 'ca@test.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'company_id' => 5,
        ]);

        $this->actingAs($user);
        $this->assertSame(5, getCurrentUserCompanyId());
    }
}
