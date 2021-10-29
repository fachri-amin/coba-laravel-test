<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Utils\ApiResponse;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function can_read_all_category () {
        $this->withoutExceptionHandling();
        $category = Category::factory(2)->create();

        // dd($category[0]->name);

        $response = $this->get('api/categories');
        // $response->assertJson(ApiResponse::success($category, 'Success get data category'));
        $response->assertSee($category[0]->name);
        $response->assertJsonStructure([
            'status',
            'message',
            'data',
        ]);
    }

    /** @test */
    public function can_show_detail_category () {
        $category = Category::factory()->create();

        $response = $this->get('api/categories/'.$category->id);

        $response->assertSee($category->name);
        $response->assertJsonStructure([
            'status',
            'message',
            'data',
        ]);
    }

    /** @test */
    public function can_create_new_catergory () {
        $category = Category::factory()->make();

        $response = $this->post('api/categories', $category->toArray());
        $this->assertEquals(1, Category::all()->count());
        $response->assertSee('Success Add Data Category');
    }

    /** @test */
    public function a_new_category_requires_name () {
        $category = Category::factory()->make(['name' => null]);
        $this->post('api/categories', $category->toArray())
            ->assertStatus(422);
    }

    /** @test */
    public function can_edit_category () {
        $category = Category::factory()->create();

        $category->name = 'Category name updated';

        $response = $this->put('api/categories/'.$category->id, $category->toArray());

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Category name updated']);
    }

    /** @test */
    public function updated_category_requires_name () {
        $category = Category::factory()->create();

        $category->name = null;

        $response = $this->put('api/categories/'.$category->id, $category->toArray())
            ->assertStatus(422);
    }

    /** @test */
    public function can_delete_category () {
        $category = Category::factory()->create();

        $response = $this->delete('categories/'.$category->id);
        $this->assertDatabaseMissing('categories', $category->toArray());
    }
}
