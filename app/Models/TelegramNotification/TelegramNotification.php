<?php

namespace App\Models\TelegramNotification;

use Carbon\Carbon;
use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;

/**
 * Class TelegramNotification
 *
 * @property integer $id
 * @property string $type
 * @property integer $product_id
 * @property string $message
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TelegramNotification extends BaseModel
{
    const TYPE_PRODUCT_LEFTOVER = 'product_leftover';

	/**
	 * @var array
	 */
	protected $fillable = ['type', 'product_id', 'message', 'created_at', 'updated_at'];
}
