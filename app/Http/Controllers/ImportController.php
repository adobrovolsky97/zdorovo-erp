<?php

namespace App\Http\Controllers;

use App\Enum\ImportExport\Type;
use App\Http\Requests\Import\UploadRequest;
use App\Http\Resources\Import\ImportResource;
use App\Services\Import\Contracts\ImportServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Response;

/**
 * Class ImportController
 */
class ImportController extends Controller
{
    /**
     * @var ImportServiceInterface
     */
    protected ImportServiceInterface $importService;

    /**
     * @param ImportServiceInterface $importService
     */
    public function __construct(ImportServiceInterface $importService)
    {
        $this->importService = $importService;
    }

    /**
     * @param Type $type
     * @param UploadRequest $request
     * @return JsonResponse
     */
    public function upload(Type $type, UploadRequest $request): JsonResponse
    {
        $this->importService->upload($type, $request->file('file'), $request->validated());

        return Response::json();
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ImportResource::collection($this->importService->getAllPaginated());
    }
}
