<?php

namespace App\Models\Warehouse;

use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * Class Warehouse
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Product[] $products
 */
class Warehouse extends BaseModel
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'uuid',
        'name'
    ];

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'warehouse_products', 'warehouse_id', 'product_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
