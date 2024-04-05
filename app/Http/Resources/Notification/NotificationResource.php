<?php

namespace App\Http\Resources\Notification;

use App\Models\Notification\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class NotificationResource
 *
 * @mixin Notification
 */
class NotificationResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'body'       => $this->body,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
