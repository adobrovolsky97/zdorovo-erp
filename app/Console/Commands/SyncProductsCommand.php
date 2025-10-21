<?php

namespace App\Console\Commands;

use App\Models\Product\Product;
use App\Services\Bimpsoft\Contracts\BimpsoftServiceInterface;
use App\Services\Product\Contracts\ProductServiceInterface;
use Illuminate\Console\Command;

class SyncProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync products by barcode with bimpsoft';

    /**
     * Execute the console command.
     */
    public function handle(BimpsoftServiceInterface $bimpsoftService, ProductServiceInterface $productService): void
    {
        $preloadedProducts = $productService->find([['barcode', 'not_null']]);
        $page = 1;

        if ($preloadedProducts->isEmpty()) {
            $this->warn('No products found in the database to sync with bimpsoft');
            return;
        }

        while (!empty($products = $bimpsoftService->getProducts($page))) {

            foreach ($products as $product) {
                /** @var Product $productToUpdate */
                if (!empty($product['sku'])) {
                    foreach ($preloadedProducts->where('barcode', $product['sku'])->whereNull('deleted_at') as $productToUpdate) {
                        $this->info('Product with barcode ' . $product['sku'] . ' found in the database. Updating...');
                        $productToUpdate->update([
                            'bimpsoft_name' => $product['name'] ?? null,
                            'bimpsoft_uuid' => $product['uuid'],
                            'uktzd'         => empty($product['article']) ? null : $product['article'],
                        ]);
                    }
                }
            }

            $page++;
        }
    }
}
