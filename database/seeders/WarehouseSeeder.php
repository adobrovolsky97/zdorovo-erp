<?php

namespace Database\Seeders;

use App\Services\Warehouse\Contracts\WarehouseServiceInterface;
use Illuminate\Database\Seeder;

/**
 * Class WarehouseSeeder
 */
class WarehouseSeeder extends Seeder
{
    /**
     * @var array|array[]
     */
    protected array $warehouses = [
        [
            'uuid' => '5b845984-8ed7-4113-8de0-09a213f17d9d',
            'name' => 'Фасувальний склад',
        ],
        [
            'uuid' => 'f01a4fe4-a2e6-4e2e-8ff4-dac656608336',
            'name' => 'Основний Склад',
        ],
        [
            'uuid' => '623591fe-c348-434c-ab4e-b2a769bf37b4',
            'name' => 'Виробничий склад',
        ],
        [
            'uuid' => '67c44319-3818-4592-b957-eabf27115928',
            'name' => 'Готова продукція склад',
        ],
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->warehouses as $warehouse) {
            app(WarehouseServiceInterface::class)->create($warehouse);
        }
    }
}
