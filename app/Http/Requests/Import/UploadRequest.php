<?php

namespace App\Http\Requests\Import;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UploadRequest
 */
class UploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:csv,xlsx,xls,ods',
            ],
        ];
    }
}
