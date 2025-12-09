<?php

namespace App\Dtos\V1\Tasks;

use Spatie\LaravelData\Dto;

class TaskDto extends Dto
{
    public function __construct(
        public int $id,
        public string $title,
        public string $description,
        public bool $status,
        public int $category_id,
        public int $user_id

    ) {}
}
