<?php

namespace App\Repositories\Export;

use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;
use App\Models\Export\Export;
use App\Repositories\Export\Contracts\ExportRepositoryInterface;

/**
 * Class ExportRepository
 */
class ExportRepository extends BaseRepository implements ExportRepositoryInterface
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Export::class;
    }
}
