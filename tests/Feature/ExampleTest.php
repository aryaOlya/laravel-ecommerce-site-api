<?php

namespace Tests\Feature;

use App\Models\Api\v1\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $user = User::factory(5)->create();
        $this->assertDatabaseCount('users', 5);
    }
}
