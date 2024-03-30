<?php

namespace App\Services\Package;

use Adobrovolsky97\LaravelRepositoryServicePattern\Exceptions\Service\ServiceException;
use App\Enum\Package\Status;
use App\Models\Package\Package;
use App\Services\Package\Contracts\PackageServiceInterface;
use App\Repositories\Package\Contracts\PackageRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class PackageService
 */
class PackageService extends BaseCrudService implements PackageServiceInterface
{
    /**
     * Add product to package
     *
     * @param Model $product
     * @param int $quantity
     * @return Package
     * @throws ServiceException
     */
    public function addProduct(Model $product, int $quantity): Package
    {
        /** @var Package $package */
        $package = $this->find(['status' => Status::PENDING->value])->first();

        if (empty($package)) {
            $package = $this->create(['status' => Status::PENDING, 'packer_id' => auth()->guard('packer')->id()]);
        }

        $package->products()->syncWithPivotValues($product, ['quantity' => $quantity], false);

        return $package->refresh();
    }

    /**
     * Delete product from package
     *
     * @param Model $product
     * @return Model
     */
    public function deleteProduct(Model $product): Model
    {
        /** @var Package $package */
        $package = $this->find(['status' => Status::PENDING->value])->first();

        if (empty($package)) {
            throw new BadRequestHttpException('Package not found');
        }

        $package->products()->detach($product);

        return $package->refresh();
    }

    /**
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return PackageRepositoryInterface::class;
    }
}
