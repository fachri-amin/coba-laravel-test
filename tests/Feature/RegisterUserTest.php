<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_register_page_can_be_rendered () {
        $this->get('/register')->assertStatus(200);
    }

    public function test_register() {

        // $this->withoutExceptionHandling();

        $user = User::factory()->make();

        $response = $this->post('/register', $user->toArray());

        $this->assertAuthenticated();

        $response->assertRedirect('/home');
    }
}
