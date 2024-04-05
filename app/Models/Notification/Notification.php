<?php

namespace App\Models\Notification;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Notification
 *
 * @property integer $id
 * @property string $body
 * @property bool $is_read
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Notification extends Model
{
    protected $fillable = [
        'body',
        'is_read'
    ];
}
