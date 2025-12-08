<?php

namespace App\Dtos\V1;

use Spatie\LaravelData\Data;

class UserRequestDto extends Data
{
    public function __construct(
        public string $name,
        public string $email,
    ) {}
}
