<?php

namespace App\Services\Notification;

use App\Models\Notification\Notification;
use App\Services\Notification\Contracts\NotificationServiceInterface;
use App\Repositories\Notification\Contracts\NotificationRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;

/**
 * Class NotificationService
 */
class NotificationService extends BaseCrudService implements NotificationServiceInterface
{
    /**
     * Read all notifications
     *
     * @return void
     */
    public function readAll(): void
    {
        $this->repository->findMany(['is_read' => false])->each(function (Notification $notification) {
            $notification->update(['is_read' => true]);
        });
    }

    /**
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return NotificationRepositoryInterface::class;
    }
}
