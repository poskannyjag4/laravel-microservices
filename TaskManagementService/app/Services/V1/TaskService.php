<?php

namespace App\Services\V1;

use App\Dtos\V1\TaskDto;
use App\Dtos\V1\TaskPatchRequestDto;
use App\Dtos\V1\TaskPostRequestDto;
use App\Models\Task;
use App\Repositories\V1\TaskRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
    ) {}

    /**
     * @return LengthAwarePaginator<int, Task>
     */
    public function getPaginatedTasks(): LengthAwarePaginator
    {
        return $this->taskRepository->paginate(10);
    }

    public function createTask(TaskPostRequestDto $data): TaskDto
    {
        $task = $this->taskRepository->create([
            'title' => $data->title,
            'description' => $data->description,
            'user_id' => $data->user_id,
            'category_id' => $data->category_id,
            'status' => false,
        ]);

        return TaskDto::from($task);
    }

    public function getTask(int $id): TaskDto
    {
        return TaskDto::from($this->taskRepository->find($id));
    }

    public function updateTask(TaskPatchRequestDto $data, int $id): TaskDto
    {
        return TaskDto::from($this->taskRepository->update([
            'title' => $data->title,
            'description' => $data->title,
            'status' => $data->status,
            'category_id' => $data->category_id,
            'user_id' => $data->user_id,
        ], $id));
    }

    public function deleteTask(int $id): int
    {
        return $this->taskRepository->delete($id);
    }
}
