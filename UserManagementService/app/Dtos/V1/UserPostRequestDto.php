<?php

namespace App\Dtos\V1;

use Spatie\LaravelData\Data;

class UserPostRequestDto extends Data
{
    public function __construct(
        public string $name,
        public string $email,
    ) {}
}
