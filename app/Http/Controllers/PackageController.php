<?php

namespace App\Http\Controllers;

use App\Enum\Package\Status;
use App\Enum\Product\Pack;
use App\Http\Requests\Package\AddProductRequest;
use App\Http\Resources\Package\PackageResource;
use App\Models\Package\Package;
use App\Models\Package\PackageProduct;
use App\Models\Product\Product;
use App\Services\Package\Contracts\PackageServiceInterface;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Response;

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
     * @return PackageResource|JsonResponse
     */
    public function getPackage(): PackageResource|JsonResponse
    {
        if ($package = $this->packageService->find(['status' => Status::PENDING->value])->first()) {
            return PackageResource::make($package);
        }

        return Response::json(null);
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
        return PackageResource::make($this->packageService->addProduct(
            $product,
            $request->input('quantity'),
            Pack::tryFrom($request->input('pack'))
        ));
    }

    /**
     * Delete product from package
     *
     * @param PackageProduct $product
     * @return PackageResource
     */
    public function removeProduct(PackageProduct $product): PackageResource
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

        return Response::json(null, 204);
    }
}
