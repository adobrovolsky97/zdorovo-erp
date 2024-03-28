<?php

namespace App\Http\Resources\Packer;

use App\Models\Packer\Packer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class PackerResource
 *
 * @mixin Packer
 */
class PackerResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
