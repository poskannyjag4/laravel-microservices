<?php

namespace App\Rules\V1;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Translation\PotentiallyTranslatedString;

class UserExists implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            $status = Http::get("user-webserver:80/api/V1/users/{$value}")->status();
            if ($status !== 200) {
                $fail("Пользователя с :attribute {$value} не существует!");
            }
        } catch (ConnectionException $e) {
            $fail('Не возможно проверить наличие пользователя! Попробуйте позже');
        }
    }
}
