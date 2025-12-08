<?php

namespace App\Dtos\V1;

use Spatie\LaravelData\Dto;

class UserUpdateRequestDto extends Dto
{
    function __construct(
        public string $id,
        public string $name,
        public string $email,
    )
    {

    }
}
