<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class TaskRequest
 */
class TaskRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'search'    => ['nullable', 'string'],
            'order_by'  => [
                'nullable',
                'string',
                Rule::in([
                    'name',
                    'leftovers',
                    'ordered_qty',
                    'qty_to_process'
                ])
            ],
            'order_dir' => ['nullable', 'string', Rule::in(['asc', 'desc'])]
        ];
    }
}
