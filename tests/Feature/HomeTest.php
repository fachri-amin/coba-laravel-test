<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_can_be_rendered() {
        $this->withoutExceptionHandling();
        
        $user = User::factory()->create();
        
        $this->actingAs($user);
        
        $response = $this->get('/home');
        $response->assertStatus(200);
    }

    public function test_redirect_if_user_not_login () {
        $this->get('/home')->assertRedirect(route('login'));
    }
}
