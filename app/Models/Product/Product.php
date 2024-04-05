<?php

namespace App\Models\Product;

use App\Enum\Product\Pack;
use Carbon\Carbon;
use App\Models\Category\Category;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;
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
 * @property integer $leftover
 * @property string $external_id
 * @property float $price
 * @property string $bimpsoft_uuid
 * @property string $barcode
 * @property string $name
 * @property Pack $pack
 * @property boolean $is_available
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Category $category
 */
class Product extends BaseModel implements HasMedia
{
    use InteractsWithMedia;

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
        'is_available',
        'leftover',
        'created_at',
        'updated_at'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'pack' => Pack::class
    ];

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
}
