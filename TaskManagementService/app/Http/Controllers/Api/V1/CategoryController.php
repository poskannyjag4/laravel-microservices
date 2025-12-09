<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Category\CategoryGetRequest;
use App\Http\Requests\V1\Category\CategoryPostRequest;
use App\Http\Requests\V1\Category\CategoryUpdateRequest;
use App\Http\Resources\V1\CategoryResource;
use App\Repositories\V1\CategoryRepository;
use App\Services\V1\CategoryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Prettus\Validator\Exceptions\ValidatorException;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService  $categoryService,
    ) {}

    public function index(CategoryGetRequest $request): AnonymousResourceCollection
    {
        return CategoryResource::collection($this->categoryService->getPaginatedCategories($request->getIncludes()));
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

    public function show(CategoryGetRequest $request, string $id): JsonResponse|CategoryResource
    {
        try {
            return new CategoryResource($this->repository->with($request->getIncludes())->find($id));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => "Категории с id {$id} не существует",
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(CategoryUpdateRequest $request, string $id): JsonResponse|CategoryResource
    {

        $taskData = $request->validated();
        try {
            $this->repository->update($taskData, $id);

            return new CategoryResource($this->repository->find($id));
        } catch (ValidatorException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => "Категории с id {$id} не существует",
            ], 404);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            return response()->json($this->repository->delete($id), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => "Категории с id {$id} не существует",
            ], 404);
        }
    }
}
