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
                $query->where(DB::raw('LOWER(name)'), 'like', "%$search%");
            })
            ->when(!empty($searchParams['categories']), function (Builder $query) use ($searchParams) {
                $query->whereIn('category_id', $searchParams['categories']);
            })
            ->when(!empty($searchParams['labels']), function (Builder $query) use ($searchParams) {
                $query->whereIn('label', $searchParams['labels']);
            })
            ->when(!empty($searchParams['packs']), function (Builder $query) use ($searchParams) {
                $query->whereIn('pack', $searchParams['packs']);
            })
            ->when(!empty($searchParams['qty_in_stock_from']), function (Builder $query) use ($searchParams) {
                $query->whereRaw('
                    GREATEST(
                        COALESCE(qty_in_stock, 0) -
                        CASE label
                            WHEN \'big_reserve_100\' THEN 100
                            WHEN \'big_reserve_300\' THEN 300
                            WHEN \'big_reserve_500\' THEN 500
                            WHEN \'small_reserve_10\' THEN 10
                            WHEN \'no_reserve\' THEN 0
                            ELSE 0
                        END,
                        0
                    ) >= ?
    ', [$searchParams['qty_in_stock_from']]);
            })
            ->when(!empty($searchParams['qty_in_stock_to']), function (Builder $query) use ($searchParams) {
                $query->whereRaw('
                    GREATEST(
                        COALESCE(qty_in_stock, 0) -
                        CASE label
                            WHEN \'big_reserve_100\' THEN 100
                            WHEN \'big_reserve_300\' THEN 300
                            WHEN \'big_reserve_500\' THEN 500
                            WHEN \'small_reserve_10\' THEN 10
                            WHEN \'no_reserve\' THEN 0
                            ELSE 0
                        END,
                        0
                    ) <= ?
                ', [$searchParams['qty_in_stock_to']]);
            })
            ->when(!empty($search['ordered_qty_from']), function (Builder $query) use ($searchParams) {
                $query->where('ordered_qty', '>=', $searchParams['ordered_qty_from']);
            })
            ->when(!empty($searchParams['ordered_qty_to']), function (Builder $query) use ($searchParams) {
                $query->where('ordered_qty', '<=', $searchParams['ordered_qty_to']);
            })
            ->when(!empty($searchParams['qty_to_process_from']), function (Builder $query) use ($searchParams) {
                $query->where('qty_to_process', '>=', $searchParams['qty_to_process_from']);
            })
            ->when(!empty($searchParams['qty_to_process_to']), function (Builder $query) use ($searchParams) {
                $query->where('qty_to_process', '<=', $searchParams['qty_to_process_to']);
            })
            ->when(isset($searchParams['is_synced_with_crm']), function (Builder $query) use ($searchParams) {
                $searchParams['is_synced_with_crm']
                    ? $query->whereNotNull('bimpsoft_uuid')
                    : $query->whereNull('bimpsoft_uuid');
            })
            ->when(!empty($searchParams['order_by']), function (Builder $query) use ($searchParams) {
                switch ($searchParams['order_by']) {
                    case 'name':
                    case 'daily_demand':
                    case 'safety_stock':
                    case 'ordered_qty':
                    case 'label':
                    case 'pack':
                    case 'qty_to_process':
                        $query->reorder($searchParams['order_by'], $searchParams['order_dir'] ?? 'desc');
                        break;
                    case 'leftovers':
                        $query->reorder(DB::raw("
                            GREATEST(
                                COALESCE(qty_in_stock, 0) -
                                CASE label
                                    WHEN 'big_reserve_100' THEN 100
                                    WHEN 'big_reserve_300' THEN 300
                                    WHEN 'big_reserve_500' THEN 500
                                    WHEN 'small_reserve_10' THEN 10
                                    WHEN 'no_reserve' THEN 0
                                    ELSE 0
                                END,
                                0
                            )
                        "), $searchParams['order_dir'] ?? 'desc');
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
