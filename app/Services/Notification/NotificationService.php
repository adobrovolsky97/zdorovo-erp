<?php

namespace App\Services\Notification;

use App\Services\Notification\Contracts\NotificationServiceInterface;
use App\Repositories\Notification\Contracts\NotificationRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;

/**
 * Class NotificationService
 *
 * @property NotificationRepositoryInterface $repository
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
        $this->repository->readAll();
    }

    /**
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return NotificationRepositoryInterface::class;
    }
}
