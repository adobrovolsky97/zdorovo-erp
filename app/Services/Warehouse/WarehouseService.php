<?php

namespace App\Services\Warehouse;

use App\Services\Warehouse\Contracts\WarehouseServiceInterface;
use App\Repositories\Warehouse\Contracts\WarehouseRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class WarehouseService
 *
 * @property WarehouseRepositoryInterface $repository
 */
class WarehouseService extends BaseCrudService implements WarehouseServiceInterface
{
    /**
     * Get leftovers
     *
     * @param array $searchParams
     * @return LengthAwarePaginator
     */
    public function getLeftovers(array $searchParams = []): LengthAwarePaginator
    {
        return $this->repository->getLeftovers($searchParams);
    }

    /**
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return WarehouseRepositoryInterface::class;
    }
}
