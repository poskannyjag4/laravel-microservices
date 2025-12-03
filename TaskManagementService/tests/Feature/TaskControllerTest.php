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
}
