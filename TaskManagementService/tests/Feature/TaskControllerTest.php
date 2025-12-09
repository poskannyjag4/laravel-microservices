<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use refreshDatabase;

    const baseUrl = '/api/V1/tasks';

    public function test_index_return_paginated_tasks()
    {
        Task::factory()->count(10)->for(Category::factory()->create())->create();

        $response = $this->getJson(self::baseUrl);

        $response->assertOk()->assertJson(fn (AssertableJson $json) => $json->has('data', 10)
            ->has('data.0', fn (AssertableJson $json) => $json->where('type', 'tasks')
                ->has('id')
                ->has('attributes', fn (AssertableJson $json) => $json->whereType('title', 'string')
                    ->whereType('description', 'string')
                    ->whereType('status', 'boolean')
                )
                ->has('relationships', fn (AssertableJson $json) => $json->has('author')
                    ->has('category')
                )
            )
            ->hasAll(['links', 'meta'])
        );
    }

    public function test_show_returns_task()
    {
        $task = Task::factory()->for(Category::factory()->create())->create();

        $response = $this->getJson(self::baseUrl.'/'.$task->id);

        $response->assertOk()->assertJson(fn (AssertableJson $json) => $json->has('data')
            ->has('data', fn (AssertableJson $json) => $json->where('type', 'tasks')
                ->has('id')
                ->has('attributes', fn (AssertableJson $json) => $json->whereType('title', 'string')
                    ->whereType('description', 'string')
                    ->whereType('status', 'boolean')
                )
                ->has('relationships', fn (AssertableJson $json) => $json->has('author')
                    ->has('category')
                )
            ));
    }

    public function test_create_task_with_valid_data()
    {
        $category = Category::factory()->create();
        $create_data = [
            'title' => 'test_title',
            'description' => 'test_description',
            'category_id' => $category->id,
            'user_id' => 1,
        ];

        $response = $this->postJson(self::baseUrl, $create_data);

        $response->assertStatus(201)->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn (AssertableJson $json) => $json->where('type', 'tasks')
                ->has('id')
                ->has('attributes', fn (AssertableJson $json) => $json->where('title', $create_data['title'])
                    ->where('description', $create_data['description'])
                    ->where('status', null)
                )
                ->has('relationships', fn (AssertableJson $json) => $json->hasAll(['author', 'category'])
                )
            ))->assertJsonPath('data.relationships.author.data.id', $create_data['user_id'])->assertJsonPath('data.relationships.category.data.id', $category->id);
    }

    public function test_create_task_with_invalid_data()
    {
        $category = Category::factory()->create();
        $create_data = [
            'title' => '',
            'description' => 'test_description',
            'category_id' => 9999,
            'user_id' => 55555,
        ];

        $response = $this->postJson(self::baseUrl, $create_data);

        $response->assertStatus(422)->assertInvalid(['title', 'category_id', 'user_id']);
    }

    public function test_update_task()
    {
        $task = Task::factory()->for(Category::factory()->create())->create();
        $update_data = [
            'title' => 'updated_title',
            'description' => 'updated_description',
        ];

        $response = $this->patchJson(self::baseUrl.'/'.$task->id, $update_data);

        $response->assertStatus(200)->assertJson(fn (AssertableJson $json) => $json
            ->has('data', fn (AssertableJson $json) => $json->where('type', 'tasks')
                ->has('id')
                ->has('attributes', fn (AssertableJson $json) => $json->where('title', $update_data['title'])
                    ->where('description', $update_data['description'])
                    ->etc()
                )
                ->has('relationships', fn (AssertableJson $json) => $json->hasAll(['author', 'category'])
                )
            ));
    }

    public function test_delete_task()
    {
        $task = Task::factory()->for(Category::factory()->create())->create();

        $response = $this->deleteJson(self::baseUrl.'/'.$task->id);

        $response->assertStatus(200);
    }
}
