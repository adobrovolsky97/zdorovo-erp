<?php

namespace App\Http\Resources\Warehouse;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeftoverResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'warehouse_name' => $this->resource->warehouse_name,
            'name'           => $this->resource->name,
            'quantity'       => $this->resource->quantity,
        ];
    }
}
