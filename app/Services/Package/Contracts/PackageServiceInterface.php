<?php

namespace App\Services\Package\Contracts;

use Adobrovolsky97\LaravelRepositoryServicePattern\Services\Contracts\BaseCrudServiceInterface;
use App\Models\Package\Package;
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
     * @param int|null $pack
     * @return Model
     */
    public function addProduct(Model $product, int $quantity, int $pack = null): Model;

    /**
     * Delete product from package
     *
     * @param Model $product
     * @return Model
     */
    public function deleteProduct(Model $product): Model;

    /**
     * Send bimpsoft package
     *
     * @param Package $package
     * @return mixed
     */
    public function send(Package $package);
}
