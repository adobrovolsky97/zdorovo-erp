<?php
declare(strict_types=1);

namespace App\Http\Resources\ExternalApi;

use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProductResource
 *
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'external_id' => $this->external_id,
            'barcode' => $this->barcode,
            'bimpsoft_uuid' => $this->bimpsoft_uuid,
            'name' => $this->bimpsoft_name ?? $this->name,
            'uktzd' => $this->uktzd,
        ];
    }
}
