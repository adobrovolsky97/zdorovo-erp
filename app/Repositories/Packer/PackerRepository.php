<?php

namespace App\Repositories\Packer;

use App\Models\Packer\Packer;
use App\Repositories\Packer\Contracts\PackerRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Repositories\BaseRepository;

/**
 * Class PackerRepository
 */
class PackerRepository extends BaseRepository implements PackerRepositoryInterface
{
	/**
	 * @return string
	 */
	protected function getModelClass(): string
	{
		return Packer::class;
	}
}