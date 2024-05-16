<?php

namespace App\Services\Package;

use Adobrovolsky97\LaravelRepositoryServicePattern\Exceptions\Service\ServiceException;
use App\Enum\Package\Status;
use App\Enum\Product\Pack;
use App\Jobs\SendXlsFileToManagerJob;
use App\Models\Package\Package;
use App\Models\Package\PackageProduct;
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
     * @param float $quantity
     * @param Pack|null $pack
     * @return Package
     * @throws ServiceException
     */
    public function addProduct(Model|Product $product, float $quantity, Pack $pack = null): Package
    {
        if ($quantity <= 0) {
            throw new BadRequestHttpException(__('Quantity must be greater than 0'));
        }

        if (!$product->is_available || empty($product->bimpsoft_uuid)) {
            throw new BadRequestHttpException(__('Product is not available for packaging'));
        }

        if (empty($product->pack)) {
            if (empty($pack)) {
                throw new BadRequestHttpException(__('Product pack is required'));
            }

            $product->update(['pack' => $pack]);
        }

        /** @var Package $package */
        $package = $this->find(['status' => Status::PENDING->value])->first();

        if (empty($package)) {
            $package = $this->create(['status' => Status::PENDING, 'packer_id' => auth()->guard('packer')->id()]);
        }

        $package->packageProducts()->updateOrCreate(
            ['product_id' => $product->id, 'pack' => $pack ?? $product->pack],
            ['quantity' => $quantity]
        );

        return $package->refresh();
    }

    /**
     * Delete product from package
     *
     * @param Model|PackageProduct $product
     * @return Model
     */
    public function deleteProduct(Model|PackageProduct $product): Model
    {
        /** @var Package $package */
        $package = $this->find(['status' => Status::PENDING->value])->first();

        if (empty($package)) {
            throw new BadRequestHttpException('Package not found');
        }

        $package->packageProducts()->whereKey($product->id)->delete();

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

            $comments = [];
            $productsData = [];

            foreach ($package->packageProducts as $packageProduct) {
                $productsData[$packageProduct->product_id] = [
                    'nomenclatureUuid'   => $packageProduct->product->bimpsoft_uuid,
                    'percentageDiscount' => 0,
                    'reserve'            => 0,
                    'count'              => $packageProduct->quantity + ($productsData[$packageProduct->product_id]['count'] ?? 0),
                    'cost'               => $packageProduct->product->price > 0 ? $packageProduct->product->price : 1
                ];

                if ($packageProduct->pack !== $packageProduct->product->pack) {
                    $comments[] = "$packageProduct->quantity шт. товару '{$packageProduct->product->name}' було розфасовано у наступне упакування: {$packageProduct->pack->value}.";
                }
            }

            $orderUuid = $bimpsoftService->sendOrder([
                'stocks'  => array_values($productsData),
                'comment' => !empty($comments) ? implode(PHP_EOL, $comments) : null
            ]);

            $package->update(['status' => Status::SENT, 'order_uuid' => $orderUuid]);

            SendXlsFileToManagerJob::dispatch($package);
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
