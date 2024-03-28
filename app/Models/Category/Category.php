<?php

namespace App\Models\Category;

use Carbon\Carbon;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;

/**
 * Class Category
 * 
 * @property integer $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Product[]|Collection $products
 */
class Category extends BaseModel
{
	/**
	 * @var array
	 */
	protected $fillable = ['name', 'created_at', 'updated_at'];

	/**
	 * @return HasMany
	 */
	public function products(): HasMany
	{
		return $this->hasMany(Product::class, 'category_id', 'id');
	}
}