<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Category\CategoryGetRequest;
use App\Http\Resources\V1\CategoryResource;
use App\Repositories\V1\CategoryRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryRepository $repository,
    ) {}

    public function index(CategoryGetRequest $request): AnonymousResourceCollection
    {
        return CategoryResource::collection($this->repository->with($request->getIncludes())->paginate(10));
    }
}
