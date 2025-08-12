<?php

namespace App\Models\Product;

use App\Enum\Product\Label;
use App\Enum\Product\Pack;
use App\Models\Warehouse\Warehouse;
use Carbon\Carbon;
use App\Models\Category\Category;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Class Product
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $external_id
 * @property float $price
 * @property float $bimpsoft_price
 * @property string $bimpsoft_uuid
 * @property string $barcode
 * @property string $name
 * @property string $group
 * @property Pack $pack
 * @property boolean $is_available
 * @property Label $label
 * @property float $leftovers
 * @property integer $daily_demand
 * @property integer $safety_stock
 * @property float $ordered_qty
 * @property float $qty_to_process
 * @property float $qty_in_stock
 * @property Carbon $last_sync_time
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Category $category
 *
 * @property Warehouse[]|Collection $warehouses
 */
class Product extends BaseModel implements HasMedia
{
    use InteractsWithMedia, SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'category_id',
        'external_id',
        'name',
        'price',
        'bimpsoft_uuid',
        'barcode',
        'pack',
        'group',
        'is_available',
        'bimpsoft_price',
        'label',
        'leftovers',
        'ordered_qty',
        'qty_in_stock',
        'last_sync_time',
        'qty_to_process',
        'daily_demand',
        'safety_stock',
        'created_at',
        'updated_at'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'pack'           => Pack::class,
        'label'          => Label::class,
        'last_sync_time' => 'datetime'
    ];

    public function getCalculatedLeftover(): ?float
    {
        if (empty($this->label)) {
            return $this->qty_in_stock ?? 0;
        }

        $result = ($this->qty_in_stock ?? 0) - $this->label->getAmount();

        return max($result, 0);
    }

    public function getQuantityToProcess()
    {
        return $this->ordered_qty - $this->getCalculatedLeftover();
    }

    public function getNewQuantityToProcessForSafetyStock(?int $safetyStock = null): float|int|null
    {
        $safetyStock = $safetyStock ?? $this->safety_stock;

        if (empty($this->daily_demand) || empty($safetyStock)) {
            return null;
        }

        if ($safetyStock === 100) {
            return $this->ordered_qty;
        }

        $result = ($this->daily_demand * $safetyStock) - $this->getCalculatedLeftover() + $this->ordered_qty;

        if ($result < 0) {
            return null;
        }

        return $result;
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile()
            ->useFallbackUrl('/images/no-image.png')
            ->useFallbackPath(public_path('images/no-image.png'));
    }

    /**
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('image')
            ->fit(Manipulations::FIT_CROP, 200, 170)
            ->nonQueued();
    }

    /**
     * @return BelongsToMany
     */
    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'warehouse_products', 'product_id', 'warehouse_id')
            ->withPivot('quantity');
    }
}
