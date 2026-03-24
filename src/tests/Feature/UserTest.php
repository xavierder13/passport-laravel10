<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use WithFaker;
    
    /**
     * Test storing a new user with random data.
     */
    public function test_store(): void
    {   
        $this->withoutMiddleware(); // 🚀 bypass everything
        // Generate random user data
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'Password123!', // plaintext password
            'password_confirmation' => 'Password123!', // if your controller validates confirmation
            'branch_id' => $this->faker->numberBetween(1, 10),
            'position_id' => $this->faker->numberBetween(1, 5),
            'active' => $this->faker->boolean,
        ];

        // Send POST request to the API
        $response = $this->postJson('/api/users', $userData);

        // Assert API returns 200 (or whatever your controller returns)
        // $response->assertStatus(200);

        // Capture the JSON dynamically
        $json = $response->json();
        dump($json); // prints full JSON in console

        // Ensure response has data
        $this->assertNotEmpty($json);

        // Optionally, assert user was saved in the database
        // $this->assertDatabaseHas('users', ['email' => $userData['email']]);
    }
}