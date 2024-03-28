<?php

namespace App\Services\Packer;

use Adobrovolsky97\LaravelRepositoryServicePattern\Exceptions\Service\ServiceException;
use App\Services\Packer\Contracts\PackerServiceInterface;
use App\Repositories\Packer\Contracts\PackerRepositoryInterface;
use Adobrovolsky97\LaravelRepositoryServicePattern\Services\BaseCrudService;
use Hash;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PackerService
 */
class PackerService extends BaseCrudService implements PackerServiceInterface
{
    /**
     * @param array $data
     * @return Model|null
     * @throws ServiceException
     */
    public function create(array $data): ?Model
    {
        $this->updateData($data);
        return parent::create($data);
    }

    /**
     * @param $keyOrModel
     * @param array $data
     * @return Model|null
     */
    public function update($keyOrModel, array $data): ?Model
    {
        $this->updateData($data);
        return parent::update($keyOrModel, $data);
    }

    /**
     * @param array $data
     * @return void
     */
    private function updateData(array &$data): void
    {
        if (!empty($data['user_id'])) {
            $data['user_id'] = Hash::make($data['user_id']);
        }
    }

    /**
     * @return string
     */
    protected function getRepositoryClass(): string
    {
        return PackerRepositoryInterface::class;
    }
}
