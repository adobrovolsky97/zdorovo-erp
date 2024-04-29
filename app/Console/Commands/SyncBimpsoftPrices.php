<?php

namespace App\Console\Commands;

use App\Models\Product\Product;
use App\Services\Bimpsoft\Contracts\BimpsoftServiceInterface;
use App\Services\Product\Contracts\ProductServiceInterface;
use Illuminate\Console\Command;

class SyncBimpsoftPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bimpsoft:sync-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync prices with bimpsoft';

    /**
     * Execute the console command.
     */
    public function handle(ProductServiceInterface $productService, BimpsoftServiceInterface $bimpsoftService): void
    {
        $productsToSync = $productService->find([['bimpsoft_uuid', 'not_null']]);

        if ($productsToSync->isEmpty()) {
            $this->warn('No products found in the database to sync with bimpsoft');
            return;
        }


        foreach ($bimpsoftService->getPrices()['rows'] as $productPrice) {
            dd($productPrice);

            /** @var Product $productToUpdate */
            if(!empty($productToUpdate = $productsToSync->where('bimpsoft_uuid', $productPrice['GUID'])->first())) {
                $this->info('Product with uuid ' . $productPrice['GUID'] . ' found in the database. Updating...');
                $productToUpdate->update([
                    'bimpsoft_price' => $productPrice['Себестоимость']
                ]);
            }
        }

    }
}
