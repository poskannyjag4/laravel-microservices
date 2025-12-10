<?php

namespace App\Dtos\V1\Categories;

use Spatie\LaravelData\Dto;

class CategoryPostRequestDto extends Dto
{
    public function __construct(
        public string $name,
    ) {}
}
