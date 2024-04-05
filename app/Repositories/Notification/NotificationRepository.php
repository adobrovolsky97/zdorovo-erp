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
	 * @return string
	 */
	protected function getModelClass(): string
	{
		return Notification::class;
	}
}