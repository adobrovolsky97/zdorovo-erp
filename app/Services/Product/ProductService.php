<?php

namespace App\Services\Product;

use App\Services\Product\Contracts\ProductServiceInterface;
use App\Repositories\Product\Contracts\ProductRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;

/**
 * Class ProductService
 */
class ProductService extends BaseCrudService implements ProductServiceInterface
{
	/**
	 * @return string
	 */
	protected function getRepositoryClass(): string
	{
		return ProductRepositoryInterface::class;
	}
}