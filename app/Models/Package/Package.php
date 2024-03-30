<?php

namespace App\Models\Package;

use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;
use App\Enum\Package\Status;
use App\Models\Product\Product;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Package
 *
 * @property integer $id
 * @property Status $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Product[]|Collection $products
 */
class Package extends BaseModel
{
    /**
     * @var array
     */
    protected $fillable = [
        'status',
        'packer_id',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'status' => Status::class
    ];

    protected static function boot()
    {
        parent::boot();

        if (!Auth::guard('packer')->guest()) {
            static::addGlobalScope('packer', function ($builder) {
                $builder->where('packer_id', Auth::guard('packer')->id());
            });
        }
    }

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(Product::class, 'package_products', 'package_id', 'product_id')
            ->withPivot('quantity');
    }
}
