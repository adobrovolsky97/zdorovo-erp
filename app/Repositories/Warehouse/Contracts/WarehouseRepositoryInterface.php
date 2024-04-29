<?php

namespace App\Repositories\Warehouse\Contracts;

use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface WarehouseRepositoryInterface
 */
interface WarehouseRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get leftovers
     *
     * @param array $searchParams
     * @return LengthAwarePaginator
     */
    public function getLeftovers(array $searchParams = []): LengthAwarePaginator;
}
