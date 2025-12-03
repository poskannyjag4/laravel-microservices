<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use refreshDatabase;

    const baseUrl = '/api/V1/categories';
    public function test_index_returns_paginated_categories(){
        Category::factory()->count(15)->create();

        $response = $this->getJson(self::baseUrl);

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('data', 10)
                ->has('data.0', fn (AssertableJson $json) =>
                $json->where('type', 'categories')
                    ->has('id')
                    ->has('attributes', fn (AssertableJson $json) =>
                    $json->whereType('name', 'string')
                    )
                )
                ->hasAll(['links', 'meta'])
            );
    }

    public function test_index_with_include(){
        Category::factory()->count(15)->has(Task::factory()->count(2))->create();

        $response = $this->getJson(self::baseUrl . '?includeTasks');

        $response->assertStatus(200)->assertJson(fn (AssertableJson $json) => $json->has('data', 10)
            ->has('data.0', fn (AssertableJson $json) =>
            $json->where('type', 'categories')
                ->has('id')
                ->whereType('includes', 'array')
                ->has('attributes', fn (AssertableJson $json) =>
                $json->whereType('name', 'string')
                )
            )
            ->hasAll(['links', 'meta'])
        );
    }

    public function test_show_returns_category(){
        $category = Category::factory()->create();

        $response = $this->getJson(self::baseUrl . '/' . $category->id);

        $response->assertStatus(200)->assertJson(fn (AssertableJson $json) => $json->has('data')
            ->has('data', fn (AssertableJson $json) =>
            $json->where('type', 'categories')
                ->has('id')
                ->has('attributes', fn (AssertableJson $json) =>
                $json->whereType('name', 'string')
                )
            ));
    }

    public function test_update_category_with_valid_data(){
        $category = Category::factory()->create();
        $update_data = [
            'name' => 'updated_name',
        ];

        $response = $this->patchJson(self::baseUrl . '/' . $category->id, $update_data);

        $response->assertStatus(200)->assertValid()->assertJson(fn (AssertableJson $json) => $json->has('data')
            ->has('data', fn (AssertableJson $json) =>
            $json->where('type', 'categories')
                ->has('id')
                ->has('attributes', fn (AssertableJson $json) =>
                $json->whereType('name', 'string')
                )
            ));
    }
}
