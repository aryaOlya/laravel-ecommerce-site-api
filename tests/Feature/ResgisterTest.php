<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResgisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->post('/api/v1/register',['name'=>'arya','email'=>'arya@olya','password'=>'1234','confirmPassword'=>'1234','address'=>'sdferge wrf']);

        $response->assertStatus(201);
    }
}
