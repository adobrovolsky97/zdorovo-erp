<?php

namespace App\Repositories\Warehouse;

use App\Models\Warehouse\Warehouse;
use App\Repositories\Warehouse\Contracts\WarehouseRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class WarehouseRepository
 */
class WarehouseRepository extends BaseRepository implements WarehouseRepositoryInterface
{
    /**
     * Get leftovers
     *
     * @param array $searchParams
     * @return LengthAwarePaginator
     */
    public function getLeftovers(array $searchParams = []): LengthAwarePaginator
    {
        return $this->getQuery()
            ->select('warehouses.name as warehouse_name', 'products.name as name', 'warehouse_products.quantity as quantity')
            ->join('warehouse_products', 'warehouses.id', '=', 'warehouse_products.warehouse_id')
            ->join('products', 'warehouse_products.product_id', '=', 'products.id')
            ->when(!empty($searchParams['warehouse_id']), function ($query) use ($searchParams) {
                return $query->where('warehouses.id', $searchParams['warehouse_id']);
            })
            ->orderBy($searchParams['sort_by'] ?? 'name', $searchParams['sort_dir'] ?? 'asc')
            ->paginate();
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Warehouse::class;
    }
}
