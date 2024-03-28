<?php

namespace App\Http\Controllers;

use App\Enum\Package\Status;
use App\Http\Requests\Package\AddProductRequest;
use App\Http\Resources\Package\PackageResource;
use App\Models\Product\Product;
use App\Services\Package\Contracts\PackageServiceInterface;

/**
 * Class PackageController
 */
class PackageController extends Controller
{
    /**
     * @var PackageServiceInterface
     */
    private PackageServiceInterface $packageService;

    /**
     * @param PackageServiceInterface $packageService
     */
    public function __construct(PackageServiceInterface $packageService)
    {
        $this->packageService = $packageService;
    }

    /**
     * @return PackageResource
     */
    public function getPackage(): PackageResource
    {
        return PackageResource::make($this->packageService->find(['status' => Status::PENDING->value])->first());
    }

    /**
     * @param Product $product
     * @param AddProductRequest $request
     * @return PackageResource
     */
    public function addProduct(Product $product, AddProductRequest $request): PackageResource
    {
        return PackageResource::make($this->packageService->addProduct($product, $request->input('quantity')));
    }

    /**
     * @param Product $product
     * @return PackageResource
     */
    public function removeProduct(Product $product): PackageResource
    {
        return PackageResource::make($this->packageService->deleteProduct($product));
    }
}
