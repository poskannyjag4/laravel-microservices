<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Task\TaskPostRequest;
use App\Http\Requests\V1\Task\TaskUpdateRequest;
use App\Http\Resources\V1\TaskResource;
use App\Repositories\V1\TaskRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Prettus\Validator\Exceptions\ValidatorException;

class TaskController extends Controller
{
    public function __construct(
        protected TaskRepository $repository,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return TaskResource::collection($this->repository->paginate(10));
    }

    public function store(TaskPostRequest $request): JsonResponse|TaskResource
    {
        try {
            $taskData = $request->validated();

            return new TaskResource($this->repository->create($taskData));
        } catch (ValidatorException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }

    }

    public function show(string $id): JsonResponse|TaskResource
    {
        try {
            return new TaskResource($this->repository->find($id));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => "Задачи с id {$id} не существует",
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }

    }

    public function update(TaskUpdateRequest $request, string $id): JsonResponse|TaskResource
    {

        $taskData = $request->validated();
        try {
            $this->repository->update($taskData, $id);

            return new TaskResource($this->repository->find($id));
        } catch (ValidatorException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => "Задачи с id {$id} не существует",
            ], 404);
        }

    }

    public function destroy(string $id): JsonResponse
    {
        try {
            return response()->json($this->repository->delete($id), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => "Задачи с id {$id} не существует",
            ], 404);
        }
    }
}
