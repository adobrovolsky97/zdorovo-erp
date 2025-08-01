<?php

namespace App\Console\Commands;

use App\Models\Product\Product;
use Illuminate\Console\Command;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\DB;

class CalculateOrderedAmountsForProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ordered-amounts:calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate ordered amounts for products';

    const STATUSES = [
        1, // нове
        2, // прийнято в роботу
        72, // Не вдалося зв'язатися 1-й раз
        3, // відправили смс з реквізитами
        4, // сплачено
        25 // комплектується
    ];

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $page = 1;
        $orderedAmounts = [];

        do {

            $ordersData = $this->fetchOrders($page);
            $this->info('Fetched products');
            $maxPages = $ordersData['pagination']['pageCount'];

            foreach ($ordersData['data'] ?? [] as $order) {

                foreach ($order['products'] ?? [] as $product) {

                    if (empty($product['parameter'])) {
                        continue;
                    }

                    $this->info('Processing product: ' . $product['text']);
                    if (!isset($orderedAmounts[$product['parameter']])) {
                        $orderedAmounts[$product['parameter']] = [
                            'amount'      => $product['amount'],
                            'external_id' => $product['parameter']
                        ];
                        continue;
                    }

                    $orderedAmounts[$product['parameter']]['amount'] += $product['amount'];
                }
            }

            $page++;

        } while ($page <= $maxPages);

        $this->updateDbData($orderedAmounts);
        self::recalculateQtyToProcess();
        $this->info('Finished');
    }

    public static function recalculateQtyToProcess(): void
    {
        Product::query()->update(['qty_to_process' => null]);

        $products = Product::query()
            ->where('daily_demand', '>', 0)
            ->where('safety_stock', '>', 0)
            ->cursor();

        foreach ($products as $product) {
            $product->update([
                'qty_to_process' => $product->getNewQuantityToProcessForSafetyStock(),
            ]);
        }
    }


    protected function updateDbData(array $orderedAmounts): void
    {
        Product::query()->where('ordered_qty', '>', 0)->update(['ordered_qty' => 0, 'qty_to_process' => 0]);

        foreach (array_chunk($orderedAmounts, 500) as $chunk) {
            $query = "UPDATE `products` SET `ordered_qty` = CASE ";

            $ids = [];
            $cases = [];
            foreach ($chunk as $value) {
                $cases[] = "WHEN `external_id` = ? THEN ?";
                $ids[] = (string)$value['external_id'];
                $ids[] = (float)$value['amount'];
            }

            $query .= implode(" ", $cases);
            $query .= " ELSE `ordered_qty` END WHERE `external_id` IN (" . implode(',', array_fill(0, count($chunk), '?')) . ")";

            foreach ($chunk as $value) {
                $ids[] = (string)$value['external_id'];
            }

            DB::update($query, $ids);
        }
    }

    /**
     * @throws RequestException
     */
    protected function fetchOrders(int $page)
    {
        $response = \Http::withHeaders([
            'Form-Api-Key' => env('SALESDRIVE_ORDERS_API_KEY'),
            'Accept'       => 'application/json',
        ])
            ->get('https://zdorovoshop.salesdrive.me/api/order/list/', [
                'page'   => $page,
                'limit'  => 100,
                'filter' => [
                    'statusId' => self::STATUSES
                ]
            ]);

        $response->throwUnlessStatus(200);

        return $response->json();
    }
}
