<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_can_be_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Smart Fishery');
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Masuk ke Akun');
    }

    public function test_register_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Registrasi Akun');
    }

    public function test_petambak_user_can_login_and_redirected_to_petambak_dashboard(): void
    {
        User::create([
            'username' => 'Petambak Demo',
            'email' => 'petambak@test.com',
            'password' => Hash::make('password123'),
            'role' => 'petambak',
        ]);

        $response = $this->post('/login', [
            'email' => 'petambak@test.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('petambak.dashboard'));
    }

    public function test_kud_user_can_login_and_redirected_to_kud_dashboard(): void
    {
        User::create([
            'username' => 'KUD Demo',
            'email' => 'kud@test.com',
            'password' => Hash::make('password123'),
            'role' => 'kud',
        ]);

        $response = $this->post('/login', [
            'email' => 'kud@test.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('kud.dashboard'));
    }

    public function test_user_can_logout(): void
    {
        $user = User::create([
            'username' => 'Logout Demo',
            'email' => 'logout@test.com',
            'password' => Hash::make('password123'),
            'role' => 'petambak',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }
}
