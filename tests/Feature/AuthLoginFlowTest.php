<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthLoginFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_without_selecting_customer_role(): void
    {
        $admin = User::factory()->create([
            'name' => 'Admin Delisha',
            'email' => 'admin@delisha.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->from(route('login'))->post(route('login.post'), [
            'email' => 'admin@delisha.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }
}
