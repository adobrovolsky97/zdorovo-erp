<?php

namespace App\Services\Category;

use App\Services\Category\Contracts\CategoryServiceInterface;
use App\Repositories\Category\Contracts\CategoryRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;

/**
 * Class CategoryService
 */
class CategoryService extends BaseCrudService implements CategoryServiceInterface
{
	/**
	 * @return string
	 */
	protected function getRepositoryClass(): string
	{
		return CategoryRepositoryInterface::class;
	}
}