<?php

namespace App\Http\Requests\Product;

use App\Enum\Product\Label;
use App\Enum\Product\Pack;
use App\Models\Category\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateRequest
 */
class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_id'  => ['nullable', 'integer', Rule::exists(Category::getTableName(), 'id')],
            'pack'         => ['nullable', 'string', Rule::enum(Pack::class)],
            'label'        => ['nullable', 'string', Rule::enum(Label::class)],
            'safety_stock' => ['nullable', 'integer', 'min:0'],
            'is_available' => ['nullable', 'boolean'],
        ];
    }
}
