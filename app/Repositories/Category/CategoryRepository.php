<?php

namespace App\Repositories\Category;

use App\Models\Category\Category;
use App\Repositories\Category\Contracts\CategoryRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;

/**
 * Class CategoryRepository
 */
class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
	/**
	 * @return string
	 */
	protected function getModelClass(): string
	{
		return Category::class;
	}
}