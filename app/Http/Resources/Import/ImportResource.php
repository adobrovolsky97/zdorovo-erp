<?php

namespace App\Http\Resources\Import;

use App\Models\Export\Export;
use App\Models\Import\Import;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ImportResource
 *
 * @mixin Import
 */
class ImportResource extends JsonResource
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'file_path'  => $this->file_path,
            'status'     => $this->status,
            'error'      => $this->error,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
