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
