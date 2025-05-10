<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationApiTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            "email" => "ratulalmamun23@gmail.com",
            "name" => "Md. Abdullah-Al-Mamun",
            "password" => "password",
            "password_confirmation" => "password",
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'ratulalmamun23@gmail.com']);
    }

    public function test_user_can_login_and_get_token()
    {
        $user = User::factory()->create(['password' => Hash::make('password')]);
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertStatus(200)->assertJsonStructure(['data']);
        $response->assertJsonStructure([
            'data' => ['token']
        ]);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)->assertJson(['success' => true]);
    }
}
