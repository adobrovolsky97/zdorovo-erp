<?php

namespace App\Http\Resources\Package;

use App\Http\Resources\Product\ProductResource;
use App\Models\Package\Package;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PackageResource
 *
 * @mixin Package
 */
class PackageResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id ?? null,
            'packer'     => [
                'id'   => $this->packer?->id,
                'name' => $this->packer?->name,
            ],
            'status'     => $this->status->title(),
            'order_uuid' => $this->order_uuid,
            'products'   => ProductResource::collection($this->products ?? []),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
