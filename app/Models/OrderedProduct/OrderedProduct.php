<?php

namespace App\Models\OrderedProduct;

use Carbon\Carbon;
use Adobrovolsky97\LaravelRepositoryServicePattern\Models\BaseModel;

/**
 * Class OrderedProduct
 *
 * @property integer $id
 * @property integer $order_id
 * @property string $product_external_id
 * @property float $quantity
 * @property string $order_date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class OrderedProduct extends BaseModel
{
    /**
     * @var array
     */
    protected $fillable = ['order_id', 'order_date', 'product_external_id', 'quantity'];
}
