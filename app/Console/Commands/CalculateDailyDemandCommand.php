<?php

namespace App\Console\Commands;

use App\Models\OrderedProduct\OrderedProduct;
use DB;
use Http;
use Illuminate\Console\Command;

class CalculateDailyDemandCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily-demand:calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate daily demand for products';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $page = 1;

        do {

            $ordersData = $this->fetchOrders($page);
            $this->info('Fetched orders');
            $maxPages = $ordersData['pagination']['pageCount'];
            $today = today()->format('Y-m-d');

            foreach ($ordersData['data'] ?? [] as $order) {

                if (OrderedProduct::query()->where('order_id', $order['id'])->exists()) {
                    continue; // Skip if order for today already exists
                }

                $orderProducts = [];
                foreach ($order['products'] ?? [] as $product) {
                    if (empty($product['parameter'])) {
                        continue;
                    }

                    $orderProducts[] = [
                        'order_id'            => $order['id'],
                        'product_external_id' => $product['parameter'],
                        'quantity'            => $product['amount'],
                        'order_date'          => $today,
                        'created_at'          => now(),
                        'updated_at'          => now(),
                    ];
                }

                if (!empty($orderProducts)) {
                    OrderedProduct::query()->insert($orderProducts);
                }
            }

            $this->info('Processed page ' . $page . ' of ' . $maxPages);
            $page++;
            sleep(5);

        } while ($page <= $maxPages);


        $this->updateDemandData();
        CalculateOrderedAmountsForProducts::recalculateQtyToProcess();
        $this->info('Done');
    }

    protected function updateDemandData()
    {
        $now = now()->format('Y-m-d H:i:s');
        $sql = '
        UPDATE products
        JOIN (
            SELECT
                ordered_products.product_external_id,
                SUM(ordered_products.quantity) AS total_quantity
            FROM ordered_products
            WHERE ordered_products.order_date >= CURDATE() - INTERVAL 90 DAY
            GROUP BY ordered_products.product_external_id
        ) AS ordered_totals
        ON products.external_id = ordered_totals.product_external_id
            SET products.daily_demand = CEIL(ordered_totals.total_quantity / 90),
            products.updated_at = "' . $now . '"
            WHERE products.id > 0;
        ';

        DB::statement($sql);
    }

    protected function fetchOrders(int $page)
    {
        $response = Http::withHeaders([
            'Form-Api-Key' => env('SALESDRIVE_ORDERS_API_KEY'),
            'Accept'       => 'application/json',
        ])
            ->get('https://zdorovoshop.salesdrive.me/api/order/list/', [
                'page'   => $page,
                'limit'  => 100,
                'filter' => [
                    'setStatusTime' => [
                        'from' => today()->subDay()->format('Y-m-d') . ' 00:00:01',
                        'to'   => today()->format('Y-m-d') . ' 23:59:59',
                    ],
                    'statusId'      => [5, 74]
                ]
            ]);

        $response->throwUnlessStatus(200);

        return $response->json();
    }
}
