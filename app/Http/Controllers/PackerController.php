<?php

namespace App\Http\Controllers;

use App\Http\Requests\Packer\StoreRequest;
use App\Http\Requests\Packer\UpdateRequest;
use App\Http\Resources\Packer\PackerResource;
use App\Models\Packer\Packer;
use App\Services\Packer\Contracts\PackerServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class PackerController
 */
class PackerController extends Controller
{
    /**
     * @var PackerServiceInterface
     */
    private PackerServiceInterface $service;

    /**
     * @param PackerServiceInterface $service
     */
    public function __construct(PackerServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return PackerResource::collection($this->service->getAllPaginated());
    }

    /**
     * @param StoreRequest $request
     * @return PackerResource
     */
    public function store(StoreRequest $request): PackerResource
    {
        return PackerResource::make($this->service->create($request->validated()));
    }

    /**
     * @param Packer $packer
     * @param UpdateRequest $request
     * @return PackerResource
     */
    public function update(Packer $packer, UpdateRequest $request): PackerResource
    {
        return PackerResource::make($this->service->update($packer, $request->validated()));
    }

    /**
     * @param Packer $packer
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Packer $packer): JsonResponse
    {
        $this->service->delete($packer);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
