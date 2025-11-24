<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Category\CategoryGetRequest;
use App\Http\Requests\V1\Category\CategoryPostRequest;
use App\Http\Resources\V1\CategoryResource;
use App\Repositories\V1\CategoryRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Prettus\Validator\Exceptions\ValidatorException;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryRepository $repository,
    ) {}

    public function index(CategoryGetRequest $request): AnonymousResourceCollection
    {
        return CategoryResource::collection($this->repository->with($request->getIncludes())->paginate(10));
    }

    public function store(CategoryPostRequest $request): JsonResponse|CategoryResource
    {
        try {
            $taskData = $request->validated();

            return new CategoryResource($this->repository->create($taskData));
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
}
