<?php

namespace App\Models\Packer;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;
use Laravel\Passport\HasApiTokens;

/**
 * Class Packer
 *
 * @property integer $id
 * @property string $name
 * @property string $user_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Packer extends Authenticatable
{
    use HasApiTokens;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'user_id'
    ];
}
