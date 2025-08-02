<?php

namespace App\Http\Controllers;

use App\Enum\ImportExport\Type;
use App\Http\Resources\Export\ExportResource;
use App\Models\Export\Export;
use App\Services\Export\Contracts\ExportServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Response;
use Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class ExportController
 */
class ExportController extends Controller
{
    /**
     * @var ExportServiceInterface
     */
    protected ExportServiceInterface $exportService;

    /**
     * @param ExportServiceInterface $exportService
     */
    public function __construct(ExportServiceInterface $exportService)
    {
        $this->exportService = $exportService;
    }

    /**
     * Download the export file
     *
     * @param Export $export
     *
     * @return BinaryFileResponse
     */
    public function download(Export $export): BinaryFileResponse
    {
        $path = Storage::disk('public')->path($export->file_path);
        if (!file_exists($path)) {
            abort(404, 'File not found');
        }

        return response()->download($path, basename($path), [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Export data
     *
     * @param Type $type
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function export(Type $type, Request $request): JsonResponse
    {
        $this->exportService->export($type, $request->all());

        return Response::json();
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ExportResource::collection($this->exportService->getAllPaginated());
    }
}
