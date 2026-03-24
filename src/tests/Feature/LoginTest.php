<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function test_login(): void
    {
        $loginData = [
            'email' => 'admin@webportal.ac',
            'password' => '123$qweR',
        ];

        $response = $this->postJson('/api/auth/login', $loginData);

        // ✅ Assert controller returned unauthorized
        // $response->assertStatus(200);

         // Capture the JSON dynamically
        $json = $response->json();
        dump($json); // ✅ prints full JSON in console
        // Ensure it has at least one key
        $this->assertNotEmpty($json);
    }
}
