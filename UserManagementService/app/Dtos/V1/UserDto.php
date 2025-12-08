<?php

namespace App\Dtos\V1;

use Spatie\LaravelData\Dto;

class UserDto extends Dto
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email
    ) {}
}
