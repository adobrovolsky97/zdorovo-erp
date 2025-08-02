<?php

namespace App\Services\Export\Contracts;

use Adobrovolsky97\LaravelRepositoryServicePattern\Services\Contracts\BaseCrudServiceInterface;
use App\Enum\ImportExport\Type;

/**
 * Interface ExportServiceInterface
 */
interface ExportServiceInterface extends BaseCrudServiceInterface
{
    /**
     * Export data
     *
     * @param Type $type
     * @param array $params
     *
     * @return void
     */
    public function export(Type $type, array $params = []): void;
}
