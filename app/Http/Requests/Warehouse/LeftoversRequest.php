<?php

namespace App\Http\Requests\Warehouse;

use App\Models\Warehouse\Warehouse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class LeftoversRequest
 */
class LeftoversRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'search'       => ['nullable', 'string', 'max:255'],
            'warehouse_id' => ['nullable', 'integer', Rule::exists(Warehouse::getTableName(), 'id')],
            'sort_by'      => ['nullable', 'string', Rule::in(['name', 'quantity'])],
            'sort_dir'     => ['nullable', 'string', Rule::in(['asc', 'desc'])],
        ];
    }
}
