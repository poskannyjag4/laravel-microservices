<?php

namespace App\Http\Requests\V1\Category;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;

class CategoryGetRequest extends FormRequest
{
    const INCLUDES = [
        'includeTasks' => 'tasks'
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<string>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    /**
     * @return string[]
     */
    public function getIncludes(): array
    {

        $query = Arr::only(self::INCLUDES, array_keys($this->input()));

        return $query ? array_values($query) : [];
    }
}
