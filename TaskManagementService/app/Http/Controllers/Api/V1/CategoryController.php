<?php

namespace App\Http\Controllers\Api\V1;

use App\Dtos\V1\Categories\CategoryPatchRequestDto;
use App\Dtos\V1\Categories\CategoryPostRequestDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Category\CategoryGetRequest;
use App\Http\Requests\V1\Category\CategoryPostRequest;
use App\Http\Requests\V1\Category\CategoryUpdateRequest;
use App\Http\Resources\V1\CategoryResource;
use App\Services\V1\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
    ) {}

    public function index(CategoryGetRequest $request): AnonymousResourceCollection
    {
        return CategoryResource::collection($this->categoryService->getPaginatedCategories($request->getIncludes()));
    }

    public function store(CategoryPostRequest $request): CategoryResource
    {
        $taskData = CategoryPostRequestDto::from($request->validated());

        return new CategoryResource($this->categoryService->createCategory($taskData));
    }

    public function show(CategoryGetRequest $request, int $id): CategoryResource
    {

        return new CategoryResource($this->categoryService->getCategory($id, $request->getIncludes()));

    }

    public function update(CategoryUpdateRequest $request, int $id): CategoryResource
    {

        $taskData = CategoryPatchRequestDto::from($request->validated());

        return new CategoryResource($this->categoryService->updateCategory($taskData, $id));

    }

    public function destroy(int $id): JsonResponse
    {
        return response()->json($this->categoryService->deleteCategory($id), 200);

    }
}
