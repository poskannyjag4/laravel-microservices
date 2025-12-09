<?php

namespace App\Dtos\V1;

use Spatie\LaravelData\Dto;

class TaskPostRequestDto extends Dto
{
    public function __construct(
        public string $title,
        public string $description,
        public int $category_id,
        public int $user_id,
    ) {}
}
