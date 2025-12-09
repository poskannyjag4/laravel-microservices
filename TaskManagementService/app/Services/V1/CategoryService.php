<?php

namespace App\Services\V1;

use App\Models\Category;
use App\Repositories\V1\CategoryRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService
{
    function __construct(
        private CategoryRepository $categoryRepository
    )
    {

}

    /**
     * @param string[] $includes
     * @return LengthAwarePaginator<int, Category>
     */
    public function getPaginatedCategories(array $includes): LengthAwarePaginator{
       return $this->categoryRepository->with($includes)->paginate(10);
    }
}
