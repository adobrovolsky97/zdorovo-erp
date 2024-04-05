<?php

namespace App\Http\Resources\Product;

use App\Models\Packer\Packer;
use App\Models\Product\Product;
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
            'pack'               => $this->pack,
            'leftover'           => $this->leftover,
            'category'           => [
                'id'   => $this->category?->id,
                'name' => $this->category?->name
            ],
            'is_available'       => $this->is_available,
            'is_synced_with_crm' => !empty($this->bimpsoft_uuid),
            'image'              => $this->getFirstMediaUrl('image')
        ];

        if (Auth::user() instanceof Packer) {
            $data['quantity'] = $this->pivot?->quantity;
            $data['custom_pack'] = $this->pivot?->pack;
            $data['pack_id'] = $this->pivot?->id;
        }

        return $data;
    }
}
