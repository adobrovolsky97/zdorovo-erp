<?php

namespace App\Services\Export;

use Adobrovolsky97\LaravelRepositoryServicePattern\Exceptions\Service\ServiceException;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;
use App\Enum\ImportExport\Status;
use App\Enum\ImportExport\Type;
use App\Jobs\HandleExportJob;
use App\Repositories\Export\Contracts\ExportRepositoryInterface;
use App\Services\Export\Contracts\ExportServiceInterface;

/**
 * Class ExportService
 */
class ExportService extends BaseCrudService implements ExportServiceInterface
{
    /**
     * Export data
     *
     * @param Type $type
     * @param array $params
     *
     * @return void
     *
     * @throws ServiceException
     */
    public function export(Type $type, array $params = []): void
    {
        $export = $this->create([
            'name'   => $type->getName($params),
            'type'   => $type,
            'status' => Status::NEW,
            'params' => $params
        ]);

        HandleExportJob::dispatch($export);
    }

    /**
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return ExportRepositoryInterface::class;
    }
}
