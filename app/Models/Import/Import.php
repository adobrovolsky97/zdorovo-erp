<?php

namespace App\Models\Import;

use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;
use App\Enum\ImportExport\Status;
use App\Enum\ImportExport\Type;
use Carbon\Carbon;

/**
 * Class Export
 *
 * @property int $id
 * @property string $name
 * @property Type $type
 * @property string $file_path
 * @property Status $status
 * @property string $error
 * @property array $params
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Import extends BaseModel
{
    /**
     * @var array
     */
    protected $casts = [
        'status' => Status::class,
        'type'   => Type::class,
        'params' => 'array',
    ];

    /**
     * @var array
     */
    protected $fillable = ['status', 'type', 'params', 'error', 'name', 'file_path'];
}
