<?php

namespace App\Services\Warehouse\Contracts;

use Adobrovolsky97\LaravelRepositoryServicePattern\Services\Contracts\BaseCrudServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface WarehouseServiceInterface
 */
interface WarehouseServiceInterface extends BaseCrudServiceInterface
{
    /**
     * Get leftovers
     *
     * @param array $searchParams
     * @return LengthAwarePaginator
     */
    public function getLeftovers(array $searchParams = []): LengthAwarePaginator;
}
