<?php

namespace App\Http\Resources\Product;

use App\Enum\Product\Pack;
use App\Models\Packer\Packer;
use App\Models\Product\Product;
use App\Models\Warehouse\Warehouse;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ProductResource
 *
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id'                 => $this->id,
            'external_id'        => $this->external_id,
            'name'               => $this->name,
            'pack'               => $this->pack?->title(),
            'leftover'           => $this->leftover,
            'category'           => [
                'id'   => $this->category?->id,
                'name' => $this->category?->name
            ],
            'is_available'       => $this->is_available,
            'is_synced_with_crm' => !empty($this->bimpsoft_uuid),
            'image'              => $this->getFirstMediaUrl('image'),
            'leftovers'          => $this->whenLoaded('warehouses', function () use ($request) {
                return $this->warehouses
                    ->filter(function (Warehouse $warehouse) use ($request) {

                        if ($request->filled('warehouse_id') && $request->input('warehouse_id') != $warehouse->id) {
                            return false;
                        }

                        return true;
                    })
                    ->map(function ($warehouse) {
                        return [
                            'quantity' => $warehouse->pivot->quantity,
                            'name'     => $warehouse->name,
                        ];
                    });
            }),
        ];

        if (Auth::user() instanceof Packer) {
            $data['quantity'] = $this->pivot?->quantity;
            $data['custom_pack'] = Pack::tryFrom($this->pivot?->pack)?->title();
            $data['pack_id'] = $this->pivot?->id;
        }

        return $data;
    }
}
