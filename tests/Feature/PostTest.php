<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\{Post, Category, Tag, User};
use Auth;

class PostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function can_read_all_post () {
        $user = User::factory()->create();
        $this->actingAs($user);
        $this->withoutExceptionHandling();
        $tag = Tag::factory()->create();
        $category = Category::factory()->create();

        $post = Post::factory(2)->create([
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);
        $post[0]->tags()->attach($tag->id);

        // dd($post[0]->name);

        $response = $this->get('api/posts');
        $response->assertSee($post[0]->name);
        $response->assertJsonStructure([
            'status',
            'message',
            'data',
        ]);
    }

    /** @test */
    public function can_show_detail_post () {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'category_id' => $category->id,
            'user_id' => $user->id
        ]);

        $response = $this->get('api/posts/'.$post->id);

        $response->assertSee($post->name);
        $response->assertJsonStructure([
            'status',
            'message',
            'data',
        ]);
    }

    /** @test */
    public function can_create_new_post () {
        $post = Post::factory()->make();

        $response = $this->post('api/posts', $post->toArray());
        $this->assertEquals(1, Post::all()->count());
        $response->assertSee('Success Add Data post');
    }

    /** @test */
    public function a_new_post_requires_name () {
        $post = Post::factory()->make(['name' => null]);
        $this->post('api/posts', $post->toArray())
            ->assertStatus(422);
    }

    /** @test */
    public function can_edit_post () {
        $post = Post::factory()->create();

        $post->name = 'post name updated';

        $response = $this->put('api/posts/'.$post->id, $post->toArray());

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'name' => 'post name updated']);
    }

    /** @test */
    public function updated_post_requires_name () {
        $post = Post::factory()->create();

        $post->name = null;

        $response = $this->put('api/posts/'.$post->id, $post->toArray())
            ->assertStatus(422);
    }

    /** @test */
    public function can_delete_post () {
        $post = Post::factory()->create();

        $response = $this->delete('posts/'.$post->id);
        $this->assertDatabaseMissing('posts', $post->toArray());
    }
}
