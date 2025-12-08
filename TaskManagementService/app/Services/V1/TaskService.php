<?php

namespace App\Services\V1;

use App\Models\Task;
use App\Repositories\V1\TaskRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskService
{
    function __construct(
        private readonly TaskRepository $taskRepository,
    )
    {

    }

    /**
     * @return LengthAwarePaginator<int, Task>
     */
    public function getPaginatedTasks(): LengthAwarePaginator
    {
        return $this->taskRepository->paginate(10);
    }
}
