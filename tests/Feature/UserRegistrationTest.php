<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_be_registered(): void
    {
        $this->assertDatabaseCount(User::class, 0);

        $response = $this->post('/api/users', [
            'email' => 'acme@example.com',
            'password' => 'password'
        ], ['Accept' => 'Application/Json']);

        $response->assertCreated();

        $response->assertExactJson(User::first()->toArray());
        $this->assertDatabaseCount(User::class, 1);
    }
}
