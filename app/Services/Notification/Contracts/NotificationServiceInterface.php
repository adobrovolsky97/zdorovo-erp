<?php

namespace App\Services\Notification\Contracts;

use Adobrovolsky97\LaravelRepositoryServicePattern\Services\Contracts\BaseCrudServiceInterface;

/**
 * Interface NotificationServiceInterface
 */
interface NotificationServiceInterface extends BaseCrudServiceInterface
{
    /**
     * Read all notifications
     *
     * @return void
     */
    public function readAll(): void;
}
