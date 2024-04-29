<?php

namespace App\Http\Controllers;

use App\Http\Requests\Warehouse\LeftoversRequest;
use App\Http\Resources\Warehouse\LeftoverResource;
use App\Http\Resources\Warehouse\WarehouseResource;
use App\Services\Warehouse\Contracts\WarehouseServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class WarehouseController
 */
class WarehouseController extends Controller
{
    /**
     * @var WarehouseServiceInterface
     */
    private WarehouseServiceInterface $warehouseService;

    /**
     * @param WarehouseServiceInterface $warehouseService
     */
    public function __construct(WarehouseServiceInterface $warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return WarehouseResource::collection($this->warehouseService->getAll());
    }

    /**
     * @param LeftoversRequest $request
     * @return AnonymousResourceCollection
     */
    public function getLeftovers(LeftoversRequest $request): AnonymousResourceCollection
    {
        return LeftoverResource::collection($this->warehouseService->getLeftovers($request->validated()));
    }
}
