<?php

namespace App\Http\Resources\V1;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Task
 */
class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'tasks',
            'id' => $this->id,

            'attributes' => [
                'title' => $this->title,
                'description' => $this->description,
                'status' => $this->status,
            ],

            'relationships' => [
                'author' => [
                    'related' => config('USER_SERVICE', 'http:localhost:8000/api/V1/').'/users/'.$this->user_id,
                    'data' => [
                        'type' => 'users',
                        'id' => $this->user_id,
                    ],
                ],
                'category' => [
                    'related' => config('APP_URL').'/api/V1/categories/'.$this->category_id,
                    'data' => [
                        'type' => 'categories',
                        'id' => $this->category_id,
                    ],
                ],
            ],
        ];
    }
}
