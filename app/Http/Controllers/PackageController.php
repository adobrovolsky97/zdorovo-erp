<?php

namespace App\Http\Controllers;

use App\Enum\Package\Status;
use App\Http\Requests\Package\AddProductRequest;
use App\Http\Resources\Package\PackageResource;
use App\Models\Package\Package;
use App\Models\Product\Product;
use App\Services\Package\Contracts\PackageServiceInterface;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return PackageResource::collection($this->packageService->getAllPaginated());
    }

    /**
     * Get package
     *
     * @return PackageResource
     */
    public function getPackage(): PackageResource
    {
        return PackageResource::make($this->packageService->find(['status' => Status::PENDING->value])->first());
    }

    /**
     * Add product to package
     *
     * @param Product $product
     * @param AddProductRequest $request
     * @return PackageResource
     */
    public function addProduct(Product $product, AddProductRequest $request): PackageResource
    {
        return PackageResource::make($this->packageService->addProduct($product, $request->input('quantity')));
    }

    /**
     * Delete product from package
     *
     * @param Product $product
     * @return PackageResource
     */
    public function removeProduct(Product $product): PackageResource
    {
        return PackageResource::make($this->packageService->deleteProduct($product));
    }

    /**
     * Send package to crm
     *
     * @param Package $package
     * @return JsonResponse
     */
    public function send(Package $package): JsonResponse
    {
        if (Auth::guard('packer')->id() !== $package->packer_id) {
            abort(403);
        }

        $this->packageService->send($package);

        return response()->json(null, 204);
    }
}
