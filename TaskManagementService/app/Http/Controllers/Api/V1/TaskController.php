<?php

namespace App\Http\Controllers\Api\V1;

use App\Dtos\V1\TaskPatchRequestDto;
use App\Dtos\V1\TaskPostRequestDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Task\TaskPostRequest;
use App\Http\Requests\V1\Task\TaskUpdateRequest;
use App\Http\Resources\V1\TaskResource;
use App\Services\V1\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService,
    ) {}

    public function index(): AnonymousResourceCollection
    {
        return TaskResource::collection($this->taskService->getPaginatedTasks());
    }

    public function store(TaskPostRequest $request): TaskResource
    {
        $taskData = TaskPostRequestDto::from($request->validated());

        return new TaskResource($this->taskService->createTask($taskData));
    }

    public function show(int $id): TaskResource
    {
        return new TaskResource($this->taskService->getTask($id));
    }

    public function update(TaskUpdateRequest $request, int $id): JsonResponse|TaskResource
    {

        $taskData = $request->validated();

        return new TaskResource($this->taskService->updateTask(TaskPatchRequestDto::from($taskData), $id));
    }

    public function destroy(int $id): JsonResponse
    {
        return response()->json($this->taskService->deleteTask($id), 200);
    }
}
