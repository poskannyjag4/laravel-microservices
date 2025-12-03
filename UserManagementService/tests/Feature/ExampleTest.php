<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    const baseUrl = '/api/V1/users';

    public function test_index_returns_paginated_list_of_users()
    {
        User::factory()->count(15)->create();

        $response = $this->withHeader('Accept', 'application/json')->get(self::baseUrl);

        $response
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('data', 10)
                ->has('data.0', fn (AssertableJson $json) =>
                $json->where('type', 'users')
                    ->has('id')
                    ->has('attributes', fn (AssertableJson $json) =>
                    $json->hasAll(['name', 'email'])
                        ->whereType('name', 'string')
                        ->whereType('email', 'string')
                    )
                )
                ->hasAll(['links', 'meta'])
            );
    }

    public function test_show_user_returns_user_data(){
        $user = User::factory()->create();

        $response = $this->withHeader('Accept', 'application/json')->get(self::baseUrl . '/' . $user->id);

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', fn (AssertableJson $json) =>
                    $json->where('type', 'users')
                         ->where('id', $user->id)
                         ->has('attributes', fn (AssertableJson $json) =>
                            $json->where('name', $user->name)
                                 ->where('email', $user->email))));
    }


}
