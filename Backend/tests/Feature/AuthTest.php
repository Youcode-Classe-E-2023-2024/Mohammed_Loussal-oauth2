<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_auth(): void
    {

        $user = User::factory(UserFactory::class)->make();
        $name = $user->name;
        $email = $user->email;
        $password = $user->password;
        $credentials = [
            "name" => $name,
            "email" => $email,
            "password" => $password,
        ];

// Register
        $response = $this->post('/api/register', $credentials);
        $data = $response->json();
        $token = $data['data']['token'];

        // Login
        $response = $this->post('/api/login', $credentials)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ]);

        // Logout
        $response = $this->post('/api/logout')->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ]);

    }
}
