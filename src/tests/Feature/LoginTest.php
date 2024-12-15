<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\RoleSeeder;
use Tests\TestCase;
use App\Models\Role;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleSeeder::class);
    }

    public function test_a_user_can_login_with_correct_credentials()
    {
        $role = Role::firstOrCreate(['name' => 'user']);

        $user = User::factory()->create([
            'role_id' => $role->id,
            'password' => bcrypt('password'), 
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/');
    }

    public function test_a_user_cannot_login_with_incorrect_credentials()
    {
        $role = Role::firstOrCreate(['name' => 'user']);

        $user = User::factory()->create([
            'role_id' => $role->id,
            'password' => bcrypt('password'), 
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertStatus(302); 
    }
}
