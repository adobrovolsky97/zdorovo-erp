<?php

namespace App\Repositories\Notification\Contracts;

use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\Contracts\BaseRepositoryInterface;

/**
 * Interface NotificationRepositoryInterface
 */
interface NotificationRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return void
     */
    public function readAll(): void;
}
