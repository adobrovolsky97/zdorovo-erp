<?php

namespace App\Http\Requests\Package;

use App\Enum\Product\Pack;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class AddProductRequest
 */
class AddProductRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'quantity' => ['required', 'integer', 'min:1'],
            'pack'     => [
                empty($this->route('product')?->pack) ? 'required' : 'nullable',
                'string',
                Rule::enum(Pack::class)
            ],
        ];
    }
}
