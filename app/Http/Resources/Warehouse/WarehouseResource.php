<?php

namespace App\Http\Resources\Warehouse;

use App\Models\Warehouse\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class WarehouseResource
 *
 * @mixin Warehouse
 */
class WarehouseResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id'   => $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
        ];
    }
}
