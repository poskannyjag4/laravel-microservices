<?php

namespace App\Dtos\V1\Categories;

use Illuminate\Support\Collection;
use Spatie\LaravelData\Dto;
use Spatie\LaravelData\Optional;

class CategoryDto extends Dto
{
    public function __construct(
        public int $id,
        public string $name,
        public Collection|Optional $tasks
    ) {}
}
