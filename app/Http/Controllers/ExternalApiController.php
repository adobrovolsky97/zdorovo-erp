<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ExternalApi\ProductResource;
use App\Services\Product\Contracts\ProductServiceInterface;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class ExternalApiController
 */
class ExternalApiController extends Controller
{
    public function __construct(private readonly ProductServiceInterface $productService)
    {
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function getProducts(): AnonymousResourceCollection
    {
        return ProductResource::collection($this->productService->getAllPaginated([
            ['bimpsoft_uuid', 'not_null']
        ]));
    }
}
