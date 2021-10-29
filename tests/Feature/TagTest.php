<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Tag;

class TagTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function can_read_all_tag () {
        $this->withoutExceptionHandling();
        $tag = Tag::factory(2)->create();

        // dd($tag[0]->name);

        $response = $this->get('api/tags');
        // $response->assertJson(ApiResponse::success($tag, 'Success get data tag'));
        $response->assertSee($tag[0]->name);
        $response->assertJsonStructure([
            'status',
            'message',
            'data',
        ]);
    }

    /** @test */
    public function can_show_detail_tag () {
        $tag = Tag::factory()->create();

        $response = $this->get('api/tags/'.$tag->id);

        $response->assertSee($tag->name);
        $response->assertJsonStructure([
            'status',
            'message',
            'data',
        ]);
    }

    /** @test */
    public function can_create_new_tag () {
        $tag = Tag::factory()->make();

        $response = $this->post('api/tags', $tag->toArray());
        $this->assertEquals(1, Tag::all()->count());
        $response->assertSee('Success add data tag');
    }

    /** @test */
    public function a_new_tag_requires_name () {
        $tag = Tag::factory()->make(['name' => null]);
        $this->post('api/tags', $tag->toArray())
            ->assertStatus(422);
    }

    /** @test */
    public function can_edit_tag () {
        $tag = Tag::factory()->create();

        $tag->name = 'tag name updated';

        $response = $this->put('api/tags/'.$tag->id, $tag->toArray());

        $this->assertDatabaseHas('tags', ['id' => $tag->id, 'name' => 'tag name updated']);
    }

    /** @test */
    public function updated_tag_requires_name () {
        $tag = Tag::factory()->create();

        $tag->name = null;

        $response = $this->put('api/tags/'.$tag->id, $tag->toArray())
            ->assertStatus(422);
    }

    /** @test */
    public function can_delete_tag () {
        $tag = Tag::factory()->create();

        $response = $this->delete('tags/'.$tag->id);
        $this->assertDatabaseMissing('tags', $tag->toArray());
    }
}
