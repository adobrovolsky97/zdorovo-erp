<?php

namespace App\Services\Import;

use Adobrovolsky97\LaravelRepositoryServicePattern\Exceptions\Service\ServiceException;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;
use App\Enum\ImportExport\Type;
use App\Jobs\HandleImportJob;
use App\Repositories\Import\Contracts\ImportRepositoryInterface;
use App\Services\Import\Contracts\ImportServiceInterface;
use Illuminate\Http\UploadedFile;

/**
 * Class ImportService
 */
class ImportService extends BaseCrudService implements ImportServiceInterface
{
    /**
     * Import file upload
     *
     * @param Type $type
     * @param UploadedFile $file
     * @param array $params
     *
     * @return void
     * @throws ServiceException
     */
    public function upload(Type $type, UploadedFile $file, array $params = []): void
    {
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('imports', $fileName);

        $import = $this->create([
            'type'      => $type,
            'file_path' => "imports/$fileName",
            'params'    => $params,
        ]);

        HandleImportJob::dispatch($import);
    }

    /**
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return ImportRepositoryInterface::class;
    }
}
