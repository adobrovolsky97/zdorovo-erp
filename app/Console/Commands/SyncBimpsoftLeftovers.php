<?php

namespace App\Console\Commands;

use App\Models\Warehouse\Warehouse;
use App\Services\Bimpsoft\Contracts\BimpsoftServiceInterface;
use App\Services\Product\Contracts\ProductServiceInterface;
use App\Services\Warehouse\Contracts\WarehouseServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Client\RequestException;

/**
 * Class SyncBimpsoftLeftovers
 */
class SyncBimpsoftLeftovers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bimpsoft:sync-leftovers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync leftovers with bimpsoft';

    /**
     * Execute the console command.
     * @throws RequestException
     */
    public function handle(ProductServiceInterface $productService, WarehouseServiceInterface $warehouseService): void
    {
        $warehouses = $warehouseService->getAll();
        foreach ($productService->getAll([['bimpsoft_uuid', 'not_null']])->chunk(100) as $productsToSync) {
            foreach ($warehouses as $warehouse) {
                $this->syncForWarehouse($warehouse, $productsToSync);
            }
        }
    }

    /**
     * @param Warehouse $warehouse
     * @param Collection $products
     * @return void
     * @throws RequestException
     */
    protected function syncForWarehouse(Warehouse $warehouse, Collection $products): void
    {
        $data = app(BimpsoftServiceInterface::class)->getLeftovers(
            $products->pluck('bimpsoft_uuid')->toArray(),
            $warehouse->uuid
        );

        $warehouseData = [];

        foreach ($data as $leftoverData) {
            if (empty($leftoverData['leftover'])) {
                continue;
            }

            $productModel = $products->where('bimpsoft_uuid', $leftoverData['uuid'])->first();

            if (empty($productModel)) {
                $this->warn('Product with uuid ' . $leftoverData['uuid'] . ' not found in the database');
                continue;
            }

            $warehouseData[$productModel->id] = ['quantity' => $leftoverData['leftover']];

            $this->info('Leftovers for product with uuid ' . $leftoverData['uuid'] . ' in warehouse ' . $warehouse->uuid . ' synced');
        }

        $warehouse->products()->syncWithoutDetaching($warehouseData);
    }
}
