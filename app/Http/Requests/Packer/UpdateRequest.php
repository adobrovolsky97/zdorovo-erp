<?php

namespace App\Http\Requests\Packer;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreRequest
 */
class UpdateRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return  [
            'name' => ['required', 'string', 'max:255'],
            'user_id' => ['nullable', 'string'],
        ];
    }
}
