<?php

namespace App\Http\Resources\Product;

use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TaskResource
 *
 * @mixin Product
 */
class TaskResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'name'                => $this->name,
            'pack'                => $this->pack?->title(),
            'label'              => [
                'id'   => $this->label?->value,
                'name' => $this->label?->title()
            ],
            'leftovers'           => $this->getCalculatedLeftover(),
            'category'           => [
                'id'   => $this->category?->id,
                'name' => $this->category?->name
            ],
            'is_available'       => $this->is_available,
            'ordered_qty'         => $this->ordered_qty,
            'quantity_to_process' => $this->qty_to_process,
        ];
    }
}
