<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->post('/api/v1/login',['email'=>'arya@aaryaa.dev','password'=>'1234']);
        $response->assertStatus(201)
            ->assertJson(fn (AssertableJson $json)=>
            $json->has('message')
                ->has('status')
                ->has('data',fn ($json)=>
                $json->has('token')
                    ->has('user')
                )
            );
    }
}
