<?php

namespace App\Http\Resources\Export;

use App\Models\Export\Export;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ExportResource
 *
 * @mixin Export
 */
class ExportResource extends JsonResource
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
            'name'       => $this->name,
            'file_path'  => $this->file_path,
            'status'     => $this->status,
            'error'      => $this->error,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
