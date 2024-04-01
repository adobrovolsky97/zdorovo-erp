<?php

namespace App\Models\Package;

use App\Enum\Product\Pack;
use App\Models\Product\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Class PackageProduct
 *
 * @property int $id
 * @property int $package_id
 * @property int $product_id
 * @property int $quantity
 * @property Pack|null $pack
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Product $product
 */
class PackageProduct extends Pivot
{
    /**
     * @var string
     */
    protected $table = 'package_products';

    /**
     * @var string[]
     */
    protected $fillable = [
        'package_id',
        'product_id',
        'quantity',
        'pack',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'pack' => Pack::class,
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
