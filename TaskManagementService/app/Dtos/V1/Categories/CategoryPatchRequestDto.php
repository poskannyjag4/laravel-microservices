<?php

namespace App\Dtos\V1\Categories;

use Spatie\LaravelData\Dto;

class CategoryPatchRequestDto extends Dto
{
    public function __construct(
        public ?string $name,
    ) {}
}
