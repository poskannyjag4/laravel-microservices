<?php

namespace App\Repositories\V1;

use App\Models\Category;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * @mixin Category
 */
class CategoryRepository extends BaseRepository
{

    /**
     * @inheritDoc
     */
    public function model()
    {
        return Category::class;
    }
}
