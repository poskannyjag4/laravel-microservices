<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\TaskGetRequest;
use App\Http\Requests\V1\TaskPostRequest;
use App\Http\Resources\V1\TaskResource;
use App\Repositories\V1\TaskRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;
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

    /**
     * @param TaskPostRequest $request
     * @return JsonResponse|TaskResource
     */
    public function store(TaskPostRequest $request): JsonResponse|TaskResource
    {
        try {
            $taskData = $request->validated();
            return new TaskResource($this->repository->create($taskData));
        } catch (ValidatorException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }
        catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }

    }

}
