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
            'quantity' => ['required', 'numeric', 'min:0.0001'],
            'pack'     => [
                empty($this->route('product')?->pack) ? 'required' : 'nullable',
                'string',
                Rule::enum(Pack::class)
            ],
        ];
    }
}
