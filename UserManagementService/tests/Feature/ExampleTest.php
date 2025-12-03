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

        $response = $this->getJson(self::baseUrl);

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

        $response = $this->getJson(self::baseUrl . '/' . $user->id);

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', fn (AssertableJson $json) =>
                    $json->where('type', 'users')
                         ->where('id', $user->id)
                         ->has('attributes', fn (AssertableJson $json) =>
                            $json->where('name', $user->name)
                                 ->where('email', $user->email))));
    }

    public function test_create_new_user_with_valid_data(){
        $test_user = [
            'name' => 'test_name',
            'email' => 'test_email@mail.com',
        ];

        $response = $this->postJson(self::baseUrl, $test_user);

        $response->assertStatus(201)->assertValid(['name', 'email'])->assertJson(fn (AssertableJson $json) =>
        $json->has('data', fn (AssertableJson $json) =>
        $json->where('type', 'users')
             ->has('id')
             ->has('attributes', fn (AssertableJson $json) =>
            $json->where('name', $test_user['name'])
                 ->where('email', $test_user['email']))));
    }

    public function test_create_new_user_with_invalid_data(){
        $test_user = [
            'name' => '',
            'email' => 'test_email',
        ];

        $response = $this->postJson(self::baseUrl, $test_user);

        $response->assertStatus(422)->assertInvalid(['name', 'email']);
    }

    public function test_update_user_with_valid_data(){
        $user = User::factory()->create();
        $update_data = [
            'name' => 'updated_name',
        ];

        $response = $this->patchJson(self::baseUrl . '/' . $user->id, $update_data);

        $response->assertStatus(200)->assertJson(fn (AssertableJson $json) =>
        $json->has('data', fn (AssertableJson $json) =>
        $json->where('type', 'users')
            ->where('id', $user->id)
            ->has('attributes', fn (AssertableJson $json) =>
            $json->where('name', $update_data['name'])
                ->where('email', $user->email))));

    }
}
