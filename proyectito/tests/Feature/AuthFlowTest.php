<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_be_logged_in_automatically(): void
    {
        $response = $this->post('/register', [
            'name' => 'Ana',
            'email' => 'ana@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', ['email' => 'ana@example.com']);
        $this->assertAuthenticatedAs(User::where('email', 'ana@example.com')->first());
    }

    public function test_user_can_login_with_registered_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'juan@example.com',
            'password' => 'secret123',
        ]);

        $response = $this->post('/login', [
            'email' => 'juan@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }
}
