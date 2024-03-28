<?php

namespace App\Repositories\Package;

use App\Models\Package\Package;
use App\Repositories\Package\Contracts\PackageRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;

/**
 * Class PackageRepository
 */
class PackageRepository extends BaseRepository implements PackageRepositoryInterface
{
	/**
	 * @return string
	 */
	protected function getModelClass(): string
	{
		return Package::class;
	}
}