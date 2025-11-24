<?php

namespace App\Http\Requests\V1\Category;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryGetRequest extends FormRequest
{
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
        $query = $this->get('include');

        return $query ? explode(',', $query) : [];
    }
}
