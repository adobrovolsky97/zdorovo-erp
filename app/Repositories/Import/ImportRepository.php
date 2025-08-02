<?php

namespace App\Repositories\Import;

use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;
use App\Models\Import\Import;
use App\Repositories\Import\Contracts\ImportRepositoryInterface;

/**
 * Class ImportRepository
 */
class ImportRepository extends BaseRepository implements ImportRepositoryInterface
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Import::class;
    }
}
