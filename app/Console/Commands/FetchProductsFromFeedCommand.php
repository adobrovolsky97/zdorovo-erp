<?php

namespace App\Console\Commands;

use App\Models\Product\Product;
use App\Services\Product\Contracts\ProductServiceInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileCannotBeAdded;
use Throwable;

class FetchProductsFromFeedCommand extends Command
{
    private const FEED_URL = 'https://zdorovoshop.salesdrive.me/export/yml/export.yml?publicKey=-TaomzP0Lqv9n4terdPMrk1-ISHXnYJaOPCY81aMgrRZ164Yv13WJX6srZuu8fM9nl8ULo4CD4lREQzs';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and parse products from feed';

    /**
     * @var array
     */
    protected array $processedExternalIds = [];

    /**
     * @var ProductServiceInterface
     */
    protected ProductServiceInterface $productService;

    /**
     * @param ProductServiceInterface $productService
     */
    public function __construct(ProductServiceInterface $productService)
    {
        parent::__construct();
        $this->productService = $productService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $data = Http::get(self::FEED_URL)->body();
        $xml = simplexml_load_string($data);

        foreach ($xml->shop->offers->offer ?? [] as $product) {
            try {
                $this->processProduct($product);
            } catch (Throwable $e) {
                $this->error($e->getMessage());
            }
        }

        $this->productService
            ->withTrashed()
            ->find([['external_id', 'not_in', $this->processedExternalIds]])
            ->each(function (Product $product) {
                $product->delete();
            });

        $this->info('Products fetched');
    }

    /**
     * @param object $product
     * @return void
     * @throws FileCannotBeAdded
     */
    protected function processProduct(object $product): void
    {
        $id = $product->attributes()->id->__toString();
        $isAvailable = (int)$product->quantity_in_stock->__toString() > 0;

        $this->processedExternalIds[] = $id;

        $existingProduct = $this->productService->withTrashed()->find(['external_id' => $id])->first();

        $imageUrl = $this->getSmallImageUrl($product->picture->__toString());

        // check if url is valid
        $content = @file_get_contents($imageUrl);
        if ($content === false) {
            $imageUrl = $product->picture->__toString();
        }

        if (!empty($existingProduct)) {
            /** @var Product $existingProduct */
            $existingProduct->update([
                'name'         => $product->name->__toString(),
                'barcode'      => empty($product->barcode->__toString()) ? null : $product->barcode->__toString(),
                'is_available' => $isAvailable,
                'deleted_at'   => null,
            ]);

            if (!$existingProduct->hasMedia('image')) {
                $this->warn("Product with external id $id has no image");
                $existingProduct->addMediaFromUrl($imageUrl)->toMediaCollection('image');
            }

            $this->info("Product with external id $id updated");
            return;
        }

        /** @var Product $productModel */
        $productModel = $this->productService->create([
            'external_id'  => $id,
            'name'         => $product->name->__toString(),
            'barcode'      => empty($product->barcode->__toString()) ? null : $product->barcode->__toString(),
            'is_available' => $isAvailable,
        ]);

        $productModel->addMediaFromUrl($imageUrl)->toMediaCollection('image');

        $this->info("Product with external id $id created");
    }

    protected function getSmallImageUrl($url): string
    {
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        $urlWithoutExtension = str_replace("." . $extension, "", $url);
        return str_replace('/image/catalog/', '/image/cache/catalog/', $urlWithoutExtension) . '-200x170.' . $extension;
    }
}
