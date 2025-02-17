<?php

namespace App\Repositories\Product;

use Adobrovolsky97\LaravelRepositoryServicePattern\Exceptions\Repository\RepositoryException;
use App\Models\Product\Product;
use App\Repositories\Product\Contracts\ProductRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;
use DB;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ProductRepository
 */
class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * @param array $searchParams
     * @return Builder
     * @throws RepositoryException
     */
    protected function applyFilters(array $searchParams = []): Builder
    {
        return parent::applyFilters($searchParams)
            ->when(!empty($searchParams['search']), function (Builder $query) use ($searchParams) {
                $search = strtolower($searchParams['search']);
                return $query->where(DB::raw('LOWER(name)'), 'like', "%$search%");
            })
            ->when(!empty($searchParams['categories']), function (Builder $query) use ($searchParams) {
                return $query->whereIn('category_id', $searchParams['categories']);
            })
            ->when(isset($searchParams['is_synced_with_crm']), function (Builder $query) use ($searchParams) {
                return $searchParams['is_synced_with_crm']
                    ? $query->whereNotNull('bimpsoft_uuid')
                    : $query->whereNull('bimpsoft_uuid');
            })
            ->when(!empty($searchParams['order_by']), function (Builder $query) use ($searchParams) {
                switch ($searchParams['order_by']) {
                    case 'name':
                    case 'ordered_qty':
                    case 'qty_to_process':
                        $query->reorder($searchParams['order_by'], $searchParams['order_dir'] ?? 'desc');
                        break;
                    case 'leftovers':
                        $query->reorder(DB::raw("COALESCE(qty_in_stock, 0) - (case
                                     when label = 'big_reserve_100' then 100
                                     when label = 'small_reserve_10' then 10
                                     else 0 end)"), $searchParams['order_dir'] ?? 'desc');
                        break;
                }
            });
    }

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Product::class;
    }
}
