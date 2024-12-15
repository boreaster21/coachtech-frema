<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_in_user_can_logout()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/logout'); 
        $response->assertStatus(302); 

        $this->assertGuest(); 
    }

    public function test_logged_out_user_cannot_access_protected_page()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->post('/logout'); 
        $this->assertGuest(); 

        $response = $this->get('/dashboard'); 
        $response->assertRedirect('/login');
    }
}
