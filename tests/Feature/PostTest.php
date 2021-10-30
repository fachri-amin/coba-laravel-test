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
        $tag = Tag::factory()->create();
        $post->tags()->attach($tag->id);

        $response = $this->get('api/posts/'.$post->id);
        $response->assertSee($post->title);
        $response->assertSee($post->body);
        $response->assertSee($category->name);
        $response->assertSee($tag->name);
        $response->assertSee($user->email);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'title',
                'body',
                'category_id',
                'user_id',
                'created_at',
                'updated_at',
                'category',
                'tags',
                'user',
            ],
        ]);
    }

    /** @test */
    public function can_create_new_post () {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user);
        $post = Post::factory()->make();
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();

        $request_data = [
            'title' => $post->title,
            'body' => $post->body,
            'category_id' => $category->id,
            'tags' => [$tag->id]
        ];

        // dd($request_data);

        $response = $this->post('api/posts', $request_data);
        $this->assertEquals(1, Post::all()->count());
        $response->assertSee($request_data['title']);
        $response->assertSee($request_data['body']);
        $response->assertSee($user->id);
        $response->assertSee($request_data['category_id']);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'id',
                'title',
                'body',
                'category_id',
                'user_id',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    /** @test */
    public function a_new_post_requires_title () {
        $post = Post::factory()->make(['title' => null]);
        $this->post('api/posts', $post->toArray())
            ->assertStatus(302); // ini 302 karena pake request class sendiri
    }

    /** @test */
    public function a_new_post_requires_body () {
        $post = Post::factory()->make(['body' => null]);
        $this->post('api/posts', $post->toArray())
            ->assertStatus(302);
    }
    
    /** @test */
    public function a_new_post_requires_category_id () {
        $post = Post::factory()->make(['category_id' => null]);
        $this->post('api/posts', $post->toArray())
            ->assertStatus(302);
    }

    /** @test */
    public function unauthenticate_user_can_not_create_post () {
        // $user = User::factory()->create();
        // $this->actingAs($user);
        $post = Post::factory()->make();
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();

        $request_data = [
            'title' => $post->title,
            'body' => $post->body,
            'category_id' => $category->id,
            'tags' => [$tag->id]
        ];

        // dd($request_data);

        $this->json('POST','api/posts', $request_data, ['Accept' => 'application/json'])
            ->assertStatus(401);
        // $this->assertNotAuthenticated();
    }

    /** @test */
    public function can_edit_post () {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category = Category::factory()->create();
        $post = Post::factory()->create(['category_id' => $category->id]);

        $post->title = 'post title updated';
        $post->save();

        $response = $this->put('api/posts/'.$post->id, $post->toArray());

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'post title updated']);
    }

    /** @test */
    public function updated_post_requires_title () {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category = Category::factory()->create();
        $post = Post::factory()->create(['category_id' => $category->id]);

        $post->title = null;

        $response = $this->put('api/posts/'.$post->id, $post->toArray())
            ->assertStatus(302);
    }

    /** @test */
    public function updated_post_requires_body () {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category = Category::factory()->create();
        $post = Post::factory()->create(['category_id' => $category->id]);

        $post->body = null;

        $response = $this->put('api/posts/'.$post->id, $post->toArray())
            ->assertStatus(302);
    }

    /** @test */
    public function updated_post_requires_category_id () {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category = Category::factory()->create();
        $post = Post::factory()->create(['category_id' => $category->id]);

        $post->category_id = null;

        $response = $this->put('api/posts/'.$post->id, $post->toArray())
            ->assertStatus(302);
    }

    /** @test */
    public function can_delete_post () {
        $user = User::factory()->create();
        $this->actingAs($user);
        $category = Category::factory()->create();
        $post = Post::factory()->create(['category_id' => $category->id]);

        $response = $this->delete('posts/'.$post->id);
        $this->assertDatabaseMissing('posts', $post->toArray());
    }
}
