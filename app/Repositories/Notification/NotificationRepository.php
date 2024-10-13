<?php

namespace App\Repositories\Notification;

use App\Models\Notification\Notification;
use App\Repositories\Notification\Contracts\NotificationRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;

/**
 * Class NotificationRepository
 */
class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{
    /**
     * @return void
     */
    public function readAll(): void
    {
        $this->getQuery()->where('is_read', false)->update(['is_read' => true]);
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Notification::class;
    }
}
