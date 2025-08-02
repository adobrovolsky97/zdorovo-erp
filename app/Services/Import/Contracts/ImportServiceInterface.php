<?php

namespace App\Services\Import\Contracts;

use Adobrovolsky97\LaravelRepositoryServicePattern\Services\Contracts\BaseCrudServiceInterface;
use App\Enum\ImportExport\Type;
use Illuminate\Http\UploadedFile;

/**
 * Interface ImportServiceInterface
 */
interface ImportServiceInterface extends BaseCrudServiceInterface
{
    /**
     * Import file upload
     *
     * @param Type $type
     * @param UploadedFile $file
     * @param array $params
     *
     * @return void
     */
    public function upload(Type $type, UploadedFile $file, array $params = []): void;
}
