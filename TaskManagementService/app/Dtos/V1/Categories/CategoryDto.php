<?php

namespace App\Dtos\V1\Categories;

use App\Models\Task;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Dto;
use Spatie\LaravelData\Optional;

class CategoryDto extends Dto
{
    /**
     * @param int $id
     * @param string $name
     * @param Collection<int, Task>|Optional $tasks
     */
    public function __construct(
        public int $id,
        public string $name,
        public Collection|Optional $tasks
    ) {}
}
