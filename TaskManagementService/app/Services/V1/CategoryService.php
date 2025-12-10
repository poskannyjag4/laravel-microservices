<?php

namespace App\Services\V1;

use App\Dtos\V1\Categories\CategoryDto;
use App\Dtos\V1\Categories\CategoryPatchRequestDto;
use App\Dtos\V1\Categories\CategoryPostRequestDto;
use App\Models\Category;
use App\Repositories\V1\CategoryRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService
{
    public function __construct(
        private CategoryRepository $categoryRepository
    ) {}

    /**
     * @param  string[]  $includes
     * @return LengthAwarePaginator<int, Category>
     */
    public function getPaginatedCategories(array $includes): LengthAwarePaginator
    {
        return $this->categoryRepository->with($includes)->paginate(10);
    }

    public function createCategory(CategoryPostRequestDto $data)
    {
        $category = $this->categoryRepository->create([
            'name' => $data->name,
        ]);

        return CategoryDto::from($category);
    }

    public function getCategory(int $id, array $includes): CategoryDto
    {
        return CategoryDto::from($this->categoryRepository->with($includes)->find($id));
    }

    public function updateCategory(CategoryPatchRequestDto $data, int $id): CategoryDto
    {
        $category = $this->categoryRepository->update([
            'name' => $data->name,
        ], $id);

        return CategoryDto::from($category);
    }

    public function deleteCategory(int $id): int
    {
        return $this->categoryRepository->delete($id);
    }
}
