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
            'id'       => $this->id ?? null,
            'products' => ProductResource::collection($this->products ?? []),
        ];
    }
}
