<?php

namespace App\Http\Requests\Product;

use App\Enum\Product\Label;
use App\Enum\Product\Pack;
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
            'search'              => ['nullable', 'string'],
            'labels'              => ['nullable', 'array'],
            'labels.*'            => ['required', 'string', Rule::enum(Label::class)],
            'packs'               => ['nullable', 'array'],
            'packs.*'             => ['required', 'string', Rule::enum(Pack::class)],
            'qty_in_stock_from'   => ['nullable', 'numeric'],
            'qty_in_stock_to'     => ['nullable', 'numeric'],
            'ordered_qty_from'    => ['nullable', 'numeric'],
            'ordered_qty_to'      => ['nullable', 'numeric'],
            'qty_to_process_from' => ['nullable', 'numeric'],
            'qty_to_process_to'   => ['nullable', 'numeric'],
            'order_by'            => [
                'nullable',
                'string',
                Rule::in([
                    'name',
                    'leftovers',
                    'ordered_qty',
                    'qty_to_process',
                    'label',
                    'pack',
                    'daily_demand',
                    'safety_stock',
                ])
            ],
            'order_dir'           => ['nullable', 'string', Rule::in(['asc', 'desc'])]
        ];
    }
}
