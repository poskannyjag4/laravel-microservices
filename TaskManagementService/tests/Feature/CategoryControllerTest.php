<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
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
}
