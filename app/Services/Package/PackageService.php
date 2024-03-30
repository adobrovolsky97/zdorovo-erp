<?php

namespace App\Services\Package;

use Adobrovolsky97\LaravelRepositoryServicePattern\Exceptions\Service\ServiceException;
use App\Enum\Package\Status;
use App\Models\Package\Package;
use App\Models\Product\Product;
use App\Services\Bimpsoft\Contracts\BimpsoftServiceInterface;
use App\Services\Package\Contracts\PackageServiceInterface;
use App\Repositories\Package\Contracts\PackageRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;
use DB;
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
     * @param Model|Product $product
     * @param int $quantity
     * @return Package
     * @throws ServiceException
     */
    public function addProduct(Model|Product $product, int $quantity): Package
    {
        if ($quantity <= 0) {
            throw new BadRequestHttpException(__('Quantity must be greater than 0'));
        }

        if (!$product->is_available || empty($product->bimpsoft_uuid)) {
            throw new BadRequestHttpException(__('Product is not available for packaging'));
        }

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
     * Send bimpsoft package
     *
     * @param Package $package
     * @return void
     */
    public function send(Package $package): void
    {
        if ($package->products->isEmpty()) {
            throw new BadRequestHttpException('Package is empty');
        }

        $bimpsoftService = app(BimpsoftServiceInterface::class);

        DB::transaction(function () use ($package, $bimpsoftService) {

            $productsData = $package->products
                ->map(function (Product $product) {
                    return [
                        'nomenclatureUuid'   => $product->bimpsoft_uuid,
                        'percentageDiscount' => 0,
                        'reserve'            => 0,
                        'count'              => $product->pivot->quantity,
                        'cost'               => 1,
                    ];
                })
                ->toArray();

            $orderUuid = $bimpsoftService->sendOrder($productsData);

            $package->update(['status' => Status::SENT, 'order_uuid' => $orderUuid]);
        });
    }

    /**
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return PackageRepositoryInterface::class;
    }
}
