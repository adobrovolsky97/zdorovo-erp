<?php

namespace App\Http\Requests\Product;

use App\Models\Category\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class SearchRequest
 */
class SearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search'       => ['nullable', 'string'],
            'categories'   => ['nullable', 'array'],
            'categories.*' => ['integer', Rule::exists(Category::getTableName(), 'id')],
            'is_available' => ['nullable', 'boolean'],
        ];
    }
}
