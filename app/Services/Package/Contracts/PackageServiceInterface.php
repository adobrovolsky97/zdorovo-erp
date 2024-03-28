<?php

namespace App\Services\Package\Contracts;

use Adobrovolsky97\LaravelRepositoryServicePattern\Services\Contracts\BaseCrudServiceInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface PackageServiceInterface
 */
interface PackageServiceInterface extends BaseCrudServiceInterface
{
    /**
     * Add product to package
     *
     * @param Model $product
     * @param int $quantity
     * @return Model
     */
    public function addProduct(Model $product, int $quantity): Model;

    /**
     * Delete product from package
     *
     * @param Model $product
     * @return Model
     */
    public function deleteProduct(Model $product): Model;
}
